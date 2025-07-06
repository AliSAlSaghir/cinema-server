<?php

require_once __DIR__ . '/../models/Genre.php';
require_once __DIR__ . '/BaseController.php';

class GenreController extends BaseController {

  public function get_genres() {

    $genres = Genre::all();

    $result = array_map(fn($genre) => $genre->toArray(), $genres);

    respond(200, ['genres' => $result]);
  }

  public function create_genre() {
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
  }
}
