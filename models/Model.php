<?php

abstract class Model {
  protected static string $table;
  protected static string $primary_key = "id";
  protected static mysqli $db;

  public static function setDB(mysqli $mysqli) {
    static::$db = $mysqli;
  }

  protected static function getParamTypes(array $params): string {
    $types = '';
    foreach ($params as $param) {
      if (is_int($param)) $types .= 'i';
      elseif (is_float($param)) $types .= 'd';
      elseif (is_string($param)) $types .= 's';
      else $types .= 's'; // fallback
    }
    return $types;
  }

  public static function find(int $id) {
    $sql = sprintf(
      "SELECT * FROM %s WHERE %s = ?",
      static::$table,
      static::$primary_key
    );

    $query = static::$db->prepare($sql);
    $query->bind_param("i", $id);
    $query->execute();

    $data = $query->get_result()->fetch_assoc();
    return $data ? new static($data) : null;
  }

  public static function all() {
    $sql = sprintf("SELECT * FROM %s", static::$table);

    $query = static::$db->prepare($sql);
    $query->execute();

    $data = $query->get_result();
    $objects = [];

    while ($row = $data->fetch_assoc()) {
      $objects[] = new static($row);
    }

    return $objects;
  }

  public static function create(array $data) {
    $columns = array_keys($data);
    $placeholders = implode(",", array_fill(0, count($columns), "?"));
    $types = static::getParamTypes(array_values($data));

    $sql = sprintf(
      "INSERT INTO %s (%s) VALUES (%s)",
      static::$table,
      implode(",", $columns),
      $placeholders
    );

    $stmt = static::$db->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param($types, ...array_values($data));
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      $id = $stmt->insert_id;
      return static::find($id);
    }

    return null;
  }

  public function update() {
    $vars = [];
    $reflection = new ReflectionObject($this);

    foreach ($reflection->getProperties() as $property) {
      if ($property->isStatic()) continue;
      $property->setAccessible(true);
      $name = $property->getName();
      if ($name !== static::$primary_key) {
        $vars[$name] = $property->getValue($this);
      }
    }

    $primaryKeyProperty = $reflection->getProperty(static::$primary_key);
    $primaryKeyProperty->setAccessible(true);
    $pkValue = $primaryKeyProperty->getValue($this);

    $setClause = implode(", ", array_map(fn($key) => "$key = ?", array_keys($vars)));
    $values = array_values($vars);
    $values[] = $pkValue;

    $types = static::getParamTypes($values);

    $sql = sprintf(
      "UPDATE %s SET %s WHERE %s = ?",
      static::$table,
      $setClause,
      static::$primary_key
    );

    $stmt = static::$db->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param($types, ...$values);
    return $stmt->execute();
  }


  public function delete() {
    $pk = static::$primary_key;
    $sql = sprintf("DELETE FROM %s WHERE %s = ?", static::$table, $pk);

    $stmt = static::$db->prepare($sql);
    if (!$stmt) return false;

    $value = $this->getPrimaryKeyValue();
    $type = static::getParamTypes([$value]);

    $stmt->bind_param($type, $value);
    return $stmt->execute();
  }

  protected function getPrimaryKeyValue() {
    $pk = static::$primary_key;
    $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $pk)));

    if (method_exists($this, $getter)) {
      return $this->$getter();
    }

    throw new Exception("Getter method $getter not found for primary key");
  }
}
