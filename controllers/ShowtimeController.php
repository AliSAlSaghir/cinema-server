<?php

require_once __DIR__ . '/../models/Showtime.php';
require_once __DIR__ . '/BaseController.php';

class ShowtimeController extends BaseController {

  public function get_showtimes() {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $movieId = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : null;
    $auditoriumId = isset($_GET['auditorium_id']) ? (int)$_GET['auditorium_id'] : null;

    $showtimes = Showtime::filter([
      'id' => $id,
      'movie_id' => $movieId,
      'auditorium_id' => $auditoriumId
    ]);

    respond(200, ['showtimes' => array_map(fn($s) => $s->toArray(), $showtimes)]);
  }

  public function create_showtime() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
      respond(400, ['error' => 'Invalid JSON input']);
    }

    requireAdmin($input['user_id'] ?? null);

    $movieId = $input['movie_id'] ?? null;
    $auditoriumId = $input['auditorium_id'] ?? null;
    $showDate = $input['show_date'] ?? null;
    $showTime = $input['show_time'] ?? null;

    if (!$movieId || !$auditoriumId || !$showDate || !$showTime) {
      respond(400, ['error' => 'movie_id, auditorium_id, show_date, and show_time are required']);
    }

    $data = [
      'movie_id' => (int)$movieId,
      'auditorium_id' => (int)$auditoriumId,
      'show_date' => $showDate,
      'show_time' => $showTime
    ];

    $showtime = Showtime::create($data);

    if ($showtime) {
      respond(200, [
        'message' => 'Showtime created successfully',
        'showtime' => $showtime->toArray()
      ]);
    } else {
      respond(500, ['error' => 'Failed to create showtime']);
    }
  }
}
