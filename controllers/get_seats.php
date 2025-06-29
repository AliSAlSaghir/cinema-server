<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Seat.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$auditoriumId = isset($_GET['auditorium_id']) ? (int) $_GET['auditorium_id'] : null;

if (!$auditoriumId) {
  respond(400, ['error' => 'auditorium_id is required']);
}

$seats = Seat::getByAuditorium($auditoriumId);

if (!$seats) {
  respond(404, ['error' => 'No seats found for this auditorium']);
}

$data = array_map(fn($seat) => $seat->toArray(), $seats);

respond(200, ['seats' => $data]);
