<?php

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/BookingSeat.php';
require_once __DIR__ . '/../models/BookingSnack.php';
require_once __DIR__ . '/../models/Snack.php';
require_once __DIR__ . '/../models/Coupon.php';

require_once __DIR__ . '/BaseController.php';

class BookingController  extends BaseController {

  public function get_bookings() {
    $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

    $bookings = Booking::getAllWithDetails($userId);

    respond(200, ['bookings' => array_map(fn($b) => $b->toArray(), $bookings)]);
  }

  public function create_booking() {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) respond(400, ['error' => 'Invalid JSON input']);

    $userId        = $input['user_id'] ?? null;
    $showtimeId    = $input['showtime_id'] ?? null;
    $seatIds       = $input['seat_ids'] ?? [];
    $snacks        = $input['snacks'] ?? [];
    $paymentMethod = $input['payment_method'] ?? 'on_site';
    $couponCode    = $input['coupon_code'] ?? null;



    if (!$userId || !$showtimeId || empty($seatIds)) {
      respond(400, ['error' => 'user_id, showtime_id, and seat_ids are required']);
    }

    $ticketPrice = 10.0;
    $total = count($seatIds) * $ticketPrice;

    foreach ($snacks as $item) {
      $snack = Snack::find($item['snack_id']);
      if ($snack) {
        $total += $snack->toArray()['price'] * $item['quantity'];
      }
    }

    $discount = 0;
    if ($couponCode) {
      $coupon = Coupon::getValidCoupon($couponCode);
      if (!$coupon) {
        respond(400, ['error' => 'Invalid or expired coupon code']);
      }
      $discount = $coupon->calculateDiscount($total);
      $total -= $discount;
    }

    foreach ($seatIds as $seatId) {
      if (BookingSeat::isSeatBooked($seatId, $showtimeId)) {
        respond(409, ['error' => "Seat ID $seatId is already booked for this showtime"]);
      }
    }

    $data = [
      'user_id'        => $userId,
      'showtime_id'    => $showtimeId,
      'booking_time'   => date('Y-m-d H:i:s'),
      'total_price'    => round($total, 2),
      'coupon_code'    => $couponCode,
      'payment_method' => $paymentMethod,
      'payment_status' => 'pending'
    ];

    $booking = Booking::create($data);
    if (!$booking) {
      respond(500, ['error' => 'Failed to create booking']);
    }

    $bookingId = $booking->toArray()['id'];

    foreach ($seatIds as $seatId) {
      BookingSeat::create([
        'booking_id' => $bookingId,
        'seat_id' => $seatId,
        'showtime_id' => $showtimeId,
        'price' => $ticketPrice
      ]);
    }


    foreach ($snacks as $item) {
      BookingSnack::create([
        'booking_id' => $bookingId,
        'snack_id'   => $item['snack_id'],
        'quantity'   => $item['quantity']
      ]);
    }

    respond(201, [
      'message'     => 'Booking created successfully',
      'booking_id'  => $bookingId,
      'discount'    => $discount,
      'final_total' => round($total, 2)
    ]);
  }
}
