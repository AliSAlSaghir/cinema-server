<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Snack.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$snackId = isset($_GET['id']) ? intval($_GET['id']) : null;
$filterAvailable = isset($_GET['is_available']);

if ($snackId) {
  $snack = Snack::find($snackId);

  if (!$snack) {
    respond(404, ['error' => 'Snack not found']);
  }

  respond(200, ['snack' => $snack->toArray()]);
} else {
  $snacks = Snack::all();

  if ($filterAvailable) {
    $snacks = array_filter($snacks, fn($s) => $s->toArray()['is_available'] === true);
  }

  $result = array_map(fn($s) => $s->toArray(), $snacks);

  respond(200, ['snacks' => array_values($result)]);
}
