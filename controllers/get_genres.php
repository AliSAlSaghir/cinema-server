<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Genre.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$genres = Genre::all();

$result = array_map(fn($genre) => $genre->toArray(), $genres);

respond(200, ['genres' => $result]);
