<?php

require_once __DIR__ . '/Model.php';

class Snack extends Model {
  protected static string $table = 'snacks';

  private int $id;
  private string $name;
  private float $price;
  private ?string $image;
  private bool $is_available;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        if ($key === 'is_available') {
          $this->$key = (bool)$value;
        } else {
          $this->$key = $value;
        }
      }
    }
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'price' => $this->price,
      'image' => $this->image,
      'is_available' => $this->is_available
    ];
  }

  public static function existAll(array $ids): bool {
    if (empty($ids)) return true;

    $db = static::$db;
    $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM snacks WHERE id IN ($placeholders)");

    $types = str_repeat('i', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'] === count($ids);
  }
}
