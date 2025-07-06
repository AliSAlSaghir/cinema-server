<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Snack.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';

Model::setDB($mysqli);

$userId = $_POST['user_id'] ?? null;
requireAdmin($userId);

$name = $_POST['name'] ?? null;
$price = $_POST['price'] ?? null;
$isAvailable = isset($_POST['is_available']) ? (bool)$_POST['is_available'] : true;

if (!$name || !$price || !is_numeric ($price)) {
  respond(400, ['error' => 'Name and numeric price are required']);
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/../uploads/snacks/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $filename = uniqid('snack_', true) . '_' . basename($_FILES['image']['name']);
  $targetPath = $uploadDir . $filename;

  if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    respond(500, ['error' => 'Failed to upload image']);
  }

  $imagePath = 'uploads/snacks/' . $filename;
} else {
  respond(400, ['error' => 'Snack image is required']);
}

$snack = Snack::create([
  'name' => $name,
  'price' => $price,
  'image' => $imagePath,
  'is_available' => $isAvailable
]);

if ($snack) {
  respond(201, [
    'message' => 'Snack created successfully',
    'snack' => $snack->toArray()
  ]);
} else {
  respond(500, ['error' => 'Failed to create snack']);
}
