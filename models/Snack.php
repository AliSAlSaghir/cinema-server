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
        $this->$key = $value;
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
}
