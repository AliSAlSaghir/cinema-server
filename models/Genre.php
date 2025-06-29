<?php

require_once __DIR__ . '/Model.php';

class Genre extends Model {
  protected static string $table = 'genres';

  private int $id;
  private string $name;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'name' => $this->name
    ];
  }
}
