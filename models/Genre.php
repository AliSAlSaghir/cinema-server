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

  public function getId(): int {
    return $this->id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): void {
    $this->name = $name;
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'name' => $this->name
    ];
  }
}
