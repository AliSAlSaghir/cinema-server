<?php

require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';


Model::setDB($mysqli);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  respond(400, ['error' => 'Invalid JSON input']);
}

requireAdmin($input['user_id'] ?? null);

$code           = $input['code'] ?? null;
$discountPercentage  = $input['discount_percentage'] ?? null;
$expiresAt      = $input['expires_at'] ?? null;
$isActive       = $input['is_active'] ?? true;

if (!$code || !$discountPercentage) {
  respond(400, ['error' => 'Code and discount_percentage are required']);
}

$data = [
  'code' => $code,
  'discount_percentage' => $discountPercentage,
  'expires_at' => $expiresAt,
  'is_active' => (bool)$isActive
];

$coupon = Coupon::create($data);

if ($coupon) {
  respond(200, [
    'message' => 'Coupon created successfully',
    'coupon' => $coupon->toArray()
  ]);
} else {
  respond(500, ['error' => 'Failed to create coupon']);
}
