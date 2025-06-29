<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';

Model::setDB($mysqli);

$userId = $_POST['user_id'] ?? null;
requireAdmin($userId);

$title = $_POST['title'] ?? null;
$genreIds = isset($_POST['genre_ids']) ? json_decode($_POST['genre_ids'], true) : [];

if (!$title || !is_array($genreIds)) {
  respond(400, ['error' => 'Title and genre_ids are required']);
}

$posterPath = null;

if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = __DIR__ . '/../uploads/posters/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
  }

  $filename = uniqid() . '_' . basename($_FILES['poster']['name']);
  $targetPath = $uploadDir . $filename;

  if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetPath)) {
    $posterPath = 'uploads/posters/' . $filename;
  } else {
    respond(500, ['error' => 'Failed to upload poster']);
  }
} else {
  respond(400, ['error' => 'Poster image is required']);
}

$data = [
  'title' => $title,
  'description' => $_POST['description'] ?? null,
  'release_date' => $_POST['release_date'] ?? null,
  'duration_minutes' => $_POST['duration_minutes'] ?? null,
  'rating' => $_POST['rating'] ?? null,
  'poster' => $posterPath
];

$movie = Movie::create($data);

if ($movie) {
  Movie::attachGenres($movie->toArray()['id'], $genreIds);

  respond(200, [
    'message' => 'Movie created successfully',
    'movie' => $movie->toArray()
  ]);
} else {
  respond(500, ['error' => 'Failed to create movie']);
}
