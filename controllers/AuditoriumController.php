<?php

require_once __DIR__ . '/../models/Auditorium.php';
require_once __DIR__ . '/BaseController.php';

class AuditoriumController  extends BaseController {

  public function get_auditoriums() {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if ($id) {
      $auditorium = Auditorium::find($id);
      if (!$auditorium) {
        respond(404, ['error' => 'Auditorium not found']);
      }
      respond(200, ['auditorium' => $auditorium->toArray()]);
    }

    $auditoriums = Auditorium::all();

    $data = array_map(fn($a) => $a->toArray(), $auditoriums);

    respond(200, ['auditoriums' => $data]);
  }

  public function create_auditorium() {
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
  }
}
