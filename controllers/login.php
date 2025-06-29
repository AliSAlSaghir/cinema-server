<?php
require_once __DIR__ . '/../helpers/allowCORS.php';
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  respond(400, ['error' => 'Invalid JSON input']);
}

$identifier = $input['identifier'] ?? null;
$password   = $input['password'] ?? null;

if (empty($identifier) || empty($password)) {
  respond(400, ['error' => 'Email or phone number and password are required']);
}

$user = User::getByEmailOrPhone($identifier);
if (!$user) {
  respond(404, ['error' => 'User not found']);
}

if (!password_verify($password, $user->getPassword())) {
  respond(401, ['error' => 'Incorrect password']);
}

respond(200, [
  'message' => 'Login successful',
  'user'    => $user->toArray()
]);
