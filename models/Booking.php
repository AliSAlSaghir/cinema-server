<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Ticket.php';
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
      'user_id' => $this->user_id,
      'showtime_id' => $this->showtime_id,
      'booking_time' => $this->booking_time,
      'total_price' => $this->total_price,
      'coupon_code' => $this->coupon_code,
      'payment_method' => $this->payment_method,
      'payment_status' => $this->payment_status
    ];
  }
}
