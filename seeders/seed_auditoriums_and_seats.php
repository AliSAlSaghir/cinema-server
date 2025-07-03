<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';
Model::setDB($mysqli);

// Define auditoriums
$auditoriums = [
  ['name' => 'Main Hall', 'seat_rows' => 5, 'seats_per_row' => 10],
  ['name' => 'VIP Lounge', 'seat_rows' => 3, 'seats_per_row' => 6],
  ['name' => 'Balcony', 'seat_rows' => 4, 'seats_per_row' => 8],
];

foreach ($auditoriums as $auditorium) {
  // Insert auditorium
  $stmt = $mysqli->prepare("INSERT INTO auditoriums (name, seat_rows, seats_per_row) VALUES (?, ?, ?)");
  $stmt->bind_param("sii", $auditorium['name'], $auditorium['seat_rows'], $auditorium['seats_per_row']);

  if ($stmt->execute()) {
    $auditoriumId = $stmt->insert_id;
    echo "✅ Created auditorium: {$auditorium['name']} (ID: $auditoriumId)" . PHP_EOL;

    // Insert seats
    for ($row = 0; $row < $auditorium['seat_rows']; $row++) {
      $rowLabel = chr(65 + $row); // A, B, C...
      for ($seatNum = 1; $seatNum <= $auditorium['seats_per_row']; $seatNum++) {
        $seatStmt = $mysqli->prepare("INSERT INTO seats (auditorium_id, row_label, seat_number) VALUES (?, ?, ?)");
        $seatStmt->bind_param("isi", $auditoriumId, $rowLabel, $seatNum);
        if (!$seatStmt->execute()) {
          echo "❌ Failed to insert seat $rowLabel$seatNum: " . $seatStmt->error . PHP_EOL;
        }
        $seatStmt->close();
      }
    }
  } else {
    echo "❌ Failed to create auditorium: {$auditorium['name']} - " . $stmt->error . PHP_EOL;
  }

  $stmt->close();
}
