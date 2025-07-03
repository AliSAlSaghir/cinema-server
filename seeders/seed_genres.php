<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Genre.php';

Model::setDB($mysqli);

$genres = [
  'Action',
  'Comedy',
  'Drama',
  'Horror',
  'Science Fiction',
  'Romance',
  'Thriller',
  'Animation',
  'Documentary',
  'Fantasy'
];

foreach ($genres as $name) {
  $genre = Genre::create(['name' => $name]);

  if ($genre) {
    echo "Seeded genre: $name" . PHP_EOL;
  } else {
    echo "Failed to seed genre: $name" . PHP_EOL;
  }
}
