<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Auditorium.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';

Model::setDB($mysqli);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  respond(400, ['error' => 'Invalid JSON input']);
}

requireAdmin($input['user_id'] ?? null);

$name = $input['name'] ?? null;
$seatRows = $input['seat_rows'] ?? null;
$seatsPerRow = $input['seats_per_row'] ?? null;

if (!$name || !$seatRows || !$seatsPerRow) {
  respond(400, ['error' => 'Name, seat_rows, and seats_per_row are required']);
}

$data = [
  'name' => $name,
  'seat_rows' => (int)$seatRows,
  'seats_per_row' => (int)$seatsPerRow
];

$auditorium = Auditorium::create($data);

if ($auditorium) {
  $auditorium->persistSeats();

  respond(200, [
    'message' => 'Auditorium created successfully',
    'auditorium' => $auditorium->toArray()
  ]);
} else {
  respond(500, ['error' => 'Failed to create auditorium']);
}
