<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Movie.php';

Model::setDB($mysqli);

$movies = [
  [
    'title' => 'The Matrix',
    'description' => 'A computer hacker learns about the true nature of reality.',
    'release_date' => '1999-03-31',
    'duration_minutes' => 136,
    'rating' => 'R',
    'poster' => '/uploads/posters/matrix.jpeg',
    'genre_ids' => [1, 5] // Action, Sci-Fi
  ],
  [
    'title' => 'Finding Nemo',
    'description' => 'A clownfish searches for his lost son across the ocean.',
    'release_date' => '2003-05-30',
    'duration_minutes' => 100,
    'rating' => 'G',
    'poster' => '/uploads/posters/nemo.jpeg',
    'genre_ids' => [8, 6] // Animation, Romance
  ],
  [
    'title' => 'Inception',
    'description' => 'A thief enters people’s dreams to steal secrets.',
    'release_date' => '2010-07-16',
    'duration_minutes' => 148,
    'rating' => 'PG-13',
    'poster' => '/uploads/posters/inception.jpeg',
    'genre_ids' => [1, 5, 10] // Action, Sci-Fi, Fantasy
  ],
  [
    'title' => 'The Godfather',
    'description' => 'An organized crime dynasty’s aging patriarch transfers control to his reluctant son.',
    'release_date' => '1972-03-24',
    'duration_minutes' => 175,
    'rating' => 'R',
    'poster' => '/uploads/posters/godfather.jpeg',
    'genre_ids' => [3, 7] // Drama, Thriller
  ]
];

foreach ($movies as $data) {
  $stmt = $mysqli->prepare("INSERT INTO movies (title, description, release_date, duration_minutes, rating, poster, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param(
    "sssiss",
    $data['title'],
    $data['description'],
    $data['release_date'],
    $data['duration_minutes'],
    $data['rating'],
    $data['poster']
  );

  if ($stmt->execute()) {
    $movieId = $stmt->insert_id;
    echo "Seeded movie: {$data['title']}" . PHP_EOL;

    foreach ($data['genre_ids'] as $genreId) {
      $mysqli->query("INSERT INTO movie_genres (movie_id, genre_id) VALUES ($movieId, $genreId)");
    }
  } else {
    echo "Failed to seed movie: {$data['title']}" . PHP_EOL;
  }

  $stmt->close();
}
