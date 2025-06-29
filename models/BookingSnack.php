<?php

require_once __DIR__ . '/Model.php';

class BookingSnack extends Model {
  protected static string $table = 'booking_snacks';

  private int $id;
  private int $booking_id;
  private int $snack_id;
  private int $quantity;
  private float $price_per_unit;
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
      'snack_id' => $this->snack_id,
      'quantity' => $this->quantity,
      'price_per_unit' => $this->price_per_unit,
      'created_at' => $this->created_at,
    ];
  }
}
