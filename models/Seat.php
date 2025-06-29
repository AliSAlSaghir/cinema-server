<?php

require_once __DIR__ . '/Model.php';

class Seat extends Model {
  protected static string $table = 'seats';

  private int $id;
  private int $auditorium_id;
  private string $row_label;
  private int $seat_number;

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
      'auditorium_id' => $this->auditorium_id,
      'row_label' => $this->row_label,
      'seat_number' => $this->seat_number,
      'label' => $this->row_label . $this->seat_number
    ];
  }
}
