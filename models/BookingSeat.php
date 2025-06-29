<?php

require_once __DIR__ . '/Model.php';

class BookingSeat extends Model {
  protected static string $table = 'booking_seats';

  private int $id;
  private int $booking_id;
  private int $showtime_id;
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

  public static function isSeatBooked(int $seatId, int $showtimeId): bool {
    $db = static::$db;
    $sql = "SELECT bs.id
            FROM booking_seats bs
            JOIN bookings b ON bs.booking_id = b.id
            WHERE bs.seat_id = ? 
              AND b.showtime_id = ?
              AND b.payment_status IN ('pending', 'paid')";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $seatId, $showtimeId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
  }



  public function toArray(): array {
    return [
      'id' => $this->id,
      'booking_id' => $this->booking_id,
      'seat_id' => $this->seat_id,
      'showtime_id' => $this->showtime_id,
      'price' => $this->price,
      'created_at' => $this->created_at,
    ];
  }
}
