<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$movieId = isset($_GET['id']) ? intval($_GET['id']) : null;
$titleFilter = isset($_GET['title']) ? trim($_GET['title']) : null;

if ($movieId) {
  $movie = Movie::find($movieId);

  if (!$movie) {
    respond(404, ['error' => 'Movie not found']);
  }

  if ($titleFilter && stripos($movie->toArray()['title'], $titleFilter) === false) {
    respond(404, ['error' => 'Movie title does not match filter']);
  }

  respond(200, ['movie' => $movie->toArray()]);
} else {
  $movies = Movie::all();

  if ($titleFilter) {
    $movies = array_filter($movies, function ($movie) use ($titleFilter) {
      return stripos($movie->toArray()['title'], $titleFilter) !== false;
    });
  }

  $result = array_map(fn($movie) => $movie->toArray(), $movies);

  respond(200, ['movies' => array_values($result)]);
}
