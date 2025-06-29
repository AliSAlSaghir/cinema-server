<?php

require_once __DIR__ . '/Model.php';

class BookingSeat extends Model {
  protected static string $table = 'booking_seats';

  private int $id;
  private int $booking_id;
  private int $seat_id;
  private float $price;
  private string $created_at;

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
      'booking_id' => $this->booking_id,
      'seat_id' => $this->seat_id,
      'price' => $this->price,
      'created_at' => $this->created_at,
    ];
  }
}
