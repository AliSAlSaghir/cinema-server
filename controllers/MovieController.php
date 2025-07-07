<?php

require_once __DIR__ . '/../models/Movie.php';
require_once __DIR__ . '/BaseController.php';

class MovieController  extends BaseController {

  public function get_movies() {
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
  }

  public function create_movie() {
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
  }
}
