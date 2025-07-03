<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Genre.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';

Model::setDB($mysqli);

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
  respond(400, ['error' => 'Invalid JSON input']);
}

requireAdmin($input['user_id'] ?? null);

$name = trim($input['name'] ?? '');

if ($name === '') {
  respond(400, ['error' => 'Genre name is required']);
}

$data = ['name' => $name];

$genre = Genre::create($data);

if ($genre) {
  respond(200, [
    'message' => 'Genre created successfully',
    'genre' => $genre->toArray()
  ]);
} else {
  respond(500, ['error' => 'Failed to create genre']);
}
