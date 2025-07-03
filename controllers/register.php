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

$name         = $input['name'] ?? null;
$password     = $input['password'] ?? null;
$email        = $input['email'] ?? null;
$phone        = $input['phone_number'] ?? null;

if (!$name || !$password || (!$email && !$phone)) {
  respond(400, ['error' => 'Missing required fields (name, password, email or phone_number)']);
}

if ($email && User::getByEmailOrPhone($email)) {
  respond(409, ['error' => 'Email already in use']);
}

if ($phone && User::getByEmailOrPhone($phone)) {
  respond(409, ['error' => 'Phone number already in use']);
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$data = [
  'name'                      => $name,
  'email'                     => $email,
  'phone_number'              => $phone,
  'password'                  => $hashedPassword,
  'date_of_birth'             => null,
  'national_id_image'         => null,
  'profile_picture'           => null,
  'preferred_day'             => null,
  'preferred_time'            => null,
  'preferred_payment_method'  => 'on_site',
  'communication_preference'  => 'email',
  'is_admin'                  => 0,
  'membership'                => 'none',
  'created_at'                => date('Y-m-d H:i:s')
];

$user = User::create($data);

if ($user) {
  respond(200, [
    'message' => 'Registration successful',
    'user_id' => $user->getId()
  ]);
} else {
  respond(500, ['error' => 'Failed to register user']);
}
