<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Auditorium.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

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
