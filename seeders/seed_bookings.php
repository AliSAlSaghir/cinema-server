<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';
Model::setDB($mysqli);

// 1. Fetch users, showtimes, seats, snacks
$users    = $mysqli->query("SELECT id FROM users")->fetch_all(MYSQLI_ASSOC);
$showtimes = $mysqli->query("SELECT id, auditorium_id FROM showtimes")->fetch_all(MYSQLI_ASSOC);
$snacks   = $mysqli->query("SELECT id, price FROM snacks WHERE is_available = 1")->fetch_all(MYSQLI_ASSOC);
$seats    = $mysqli->query("SELECT id, auditorium_id FROM seats")->fetch_all(MYSQLI_ASSOC);

if (!$users || !$showtimes || !$seats) {
  echo "❌ Missing required data (users, showtimes, or seats)\n";
  exit;
}

// Helper: Get random snack selection
function getRandomSnacks(array $snacks): array {
  $maxSnacks = min(3, count($snacks));
  $num = rand(0, $maxSnacks);

  if ($num === 0) {
    return [];
  }

  $keys = array_rand($snacks, $num);

  if ($num === 1) {
    $keys = [$keys];
  }

  $selected = [];
  foreach ($keys as $key) {
    $selected[] = $snacks[$key];
  }

  return $selected;
}

// Seed 5-10 bookings
for ($i = 0; $i < rand(5, 10); $i++) {
  $user = $users[array_rand($users)];
  $showtime = $showtimes[array_rand($showtimes)];
  $auditoriumId = $showtime['auditorium_id'];

  // Pick random 1-3 seats for this showtime (filter by auditorium)
  $availableSeats = array_filter($seats, fn($s) => $s['auditorium_id'] == $auditoriumId);
  $seatIds = array_column($availableSeats, 'id');
  shuffle($seatIds);
  $selectedSeats = array_slice($seatIds, 0, rand(1, 3));

  // Get random snacks
  $snackItems = getRandomSnacks($snacks);

  // Calculate total price
  $ticketPrice = 10.0;
  $total = count($selectedSeats) * $ticketPrice + array_sum(array_column($snackItems, 'total'));

  $paymentMethod = rand(0, 1) ? 'online' : 'on_site';
  $paymentStatus = rand(0, 1) ? 'paid' : 'pending';

  // Insert booking
  $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, showtime_id, booking_time, total_price, payment_method, payment_status) VALUES (?, ?, NOW(), ?, ?, ?)");
  $stmt->bind_param("idsss", $user['id'], $showtime['id'], $total, $paymentMethod, $paymentStatus);

  if ($stmt->execute()) {
    $bookingId = $stmt->insert_id;
    echo "✅ Booking ID $bookingId created\n";

    // Insert booking_seats
    foreach ($selectedSeats as $seatId) {
      $s = $mysqli->prepare("INSERT INTO booking_seats (booking_id, seat_id) VALUES (?, ?)");
      $s->bind_param("ii", $bookingId, $seatId);
      $s->execute();
      $s->close();
    }

    // Insert booking_snacks
    foreach ($snackItems as $item) {
      $s = $mysqli->prepare("INSERT INTO booking_snacks (booking_id, snack_id, quantity, price_per_unit, created_at) VALUES (?, ?, ?, ?, NOW())");
      $s->bind_param("iiid", $bookingId, $item['snack_id'], $item['quantity'], $item['price_per_unit']);
      $s->execute();
      $s->close();
    }
  } else {
    echo "❌ Failed to insert booking: " . $stmt->error . "\n";
  }

  $stmt->close();
}
