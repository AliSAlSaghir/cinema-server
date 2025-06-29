<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/BookingSnack.php';

class Booking extends Model {
  protected static string $table = 'bookings';

  private int $id;
  private int $user_id;
  private int $showtime_id;
  private string $booking_time;
  private float $total_price;
  private ?string $coupon_code;
  private string $payment_method;
  private string $payment_status;
  private ?string $movie_title = null;
  private ?string $show_date = null;
  private ?string $show_time = null;


  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public static function getAllWithDetails(?int $userId = null): array {
    $db = static::$db;

    $sql = "
      SELECT 
        b.*, 
        s.show_date, 
        s.show_time,
        m.title AS movie_title
      FROM bookings b
      JOIN showtimes s ON b.showtime_id = s.id
      JOIN movies m ON s.movie_id = m.id
      WHERE 1 = 1
    ";

    $params = [];
    $types = '';

    if ($userId) {
      $sql .= " AND b.user_id = ?";
      $params[] = $userId;
      $types .= 'i';
    }

    $stmt = $db->prepare($sql);
    if ($params) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while ($row = $result->fetch_assoc()) {
      $booking = new self($row);
      $booking->movie_title = $row['movie_title'];
      $booking->show_date = $row['show_date'];
      $booking->show_time = $row['show_time'];
      $bookings[] = $booking;
    }

    return $bookings;
  }


  public function toArray(): array {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'showtime_id' => $this->showtime_id,
      'booking_time' => $this->booking_time,
      'total_price' => $this->total_price,
      'coupon_code' => $this->coupon_code,
      'payment_method' => $this->payment_method,
      'payment_status' => $this->payment_status,
      'movie_title' => $this->movie_title ?? null,
      'show_date' => $this->show_date ?? null,
      'show_time' => $this->show_time ?? null,
    ];
  }
}
