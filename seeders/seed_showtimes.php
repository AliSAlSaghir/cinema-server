<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';
Model::setDB($mysqli);

// Fetch existing movies and auditoriums
$moviesResult = $mysqli->query("SELECT id FROM movies LIMIT 3");
$auditoriumsResult = $mysqli->query("SELECT id FROM auditoriums LIMIT 2");

$movieIds = [];
$auditoriumIds = [];

while ($row = $moviesResult->fetch_assoc()) {
  $movieIds[] = $row['id'];
}

while ($row = $auditoriumsResult->fetch_assoc()) {
  $auditoriumIds[] = $row['id'];
}

// Prevent seeding if we don't have required data
if (empty($movieIds) || empty($auditoriumIds)) {
  echo "❌ Not enough movies or auditoriums to seed showtimes.\n";
  exit;
}

$showtimes = [
  ['days_from_now' => 1,  'time' => '14:00:00'],
  ['days_from_now' => 1,  'time' => '18:00:00'],
  ['days_from_now' => 2,  'time' => '20:30:00'],
  ['days_from_now' => 3,  'time' => '17:00:00'],
  ['days_from_now' => 4,  'time' => '15:00:00'],
  ['days_from_now' => 5,  'time' => '19:45:00'],
];

foreach ($showtimes as $index => $slot) {
  $movieId = $movieIds[$index % count($movieIds)];
  $auditoriumId = $auditoriumIds[$index % count($auditoriumIds)];
  $date = date('Y-m-d', strtotime("+{$slot['days_from_now']} days"));
  $time = $slot['time'];

  $stmt = $mysqli->prepare("INSERT INTO showtimes (movie_id, auditorium_id, show_date, show_time) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iiss", $movieId, $auditoriumId, $date, $time);

  if ($stmt->execute()) {
    echo "✅ Seeded showtime for movie_id $movieId in auditorium_id $auditoriumId at $date $time\n";
  } else {
    echo "❌ Failed to seed showtime: " . $stmt->error . "\n";
  }

  $stmt->close();
}
