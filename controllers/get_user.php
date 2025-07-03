<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

if (!isset($_GET['id'])) {
  respond(400, ['error' => 'Missing or invalid user ID']);
}

$id = (int) $_GET['id'];

$user = User::find($id);
if (!$user) {
  respond(404, ['error' => 'User not found']);
}

respond(200, [
  'user' => $user->toArray()
]);
