<?php
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../helpers/allowCORS.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAuth.php';

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/BookingSeat.php';
require_once __DIR__ . '/../models/BookingSnack.php';
require_once __DIR__ . '/../models/Snack.php';
require_once __DIR__ . '/../models/Coupon.php';

Model::setDB($mysqli);

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) respond(400, ['error' => 'Invalid JSON input']);

$userId        = $input['user_id'] ?? null;
$showtimeId    = $input['showtime_id'] ?? null;
$seatIds       = $input['seat_ids'] ?? [];
$snacks        = $input['snacks'] ?? [];
$paymentMethod = $input['payment_method'] ?? 'on_site';
$couponCode    = $input['coupon_code'] ?? null;

// Optionally require authentication
// requireAuth($userId);

if (!$userId || !$showtimeId || empty($seatIds)) {
  respond(400, ['error' => 'user_id, showtime_id, and seat_ids are required']);
}

// 🎟️ Ticket price (can be made dynamic later)
$ticketPrice = 10.0;
$total = count($seatIds) * $ticketPrice;

// 🍿 Add snack prices
foreach ($snacks as $item) {
  $snack = Snack::find($item['snack_id']);
  if ($snack) {
    $total += $snack->toArray()['price'] * $item['quantity'];
  }
}

// 🎟️ Apply coupon if available
$discount = 0;
if ($couponCode) {
  $coupon = Coupon::getValidCoupon($couponCode);
  if (!$coupon) {
    respond(400, ['error' => 'Invalid or expired coupon code']);
  }
  $discount = $coupon->calculateDiscount($total);
  $total -= $discount;
}

// 💺 Validate seat availability before booking
foreach ($seatIds as $seatId) {
  if (BookingSeat::isSeatBooked($seatId, $showtimeId)) {
    respond(409, ['error' => "Seat ID $seatId is already booked for this showtime"]);
  }
}

// 📝 Create booking
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


// 🍫 Save snacks
foreach ($snacks as $item) {
  BookingSnack::create([
    'booking_id' => $bookingId,
    'snack_id'   => $item['snack_id'],
    'quantity'   => $item['quantity']
  ]);
}

// ✅ Success response
respond(201, [
  'message'     => 'Booking created successfully',
  'booking_id'  => $bookingId,
  'discount'    => $discount,
  'final_total' => round($total, 2)
]);
