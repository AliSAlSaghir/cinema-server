<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$movieId = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : null;
$auditoriumId = isset($_GET['auditorium_id']) ? (int)$_GET['auditorium_id'] : null;

$showtimes = Showtime::filter([
  'id' => $id,
  'movie_id' => $movieId,
  'auditorium_id' => $auditoriumId
]);

respond(200, ['showtimes' => array_map(fn($s) => $s->toArray(), $showtimes)]);
