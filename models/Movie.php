<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Showtime.php';


class Movie extends Model {
  protected static string $table = 'movies';

  private int $id;
  private string $title;
  private ?string $description;
  private ?string $release_date;
  private ?int $duration_minutes;
  private ?string $rating;
  private ?string $poster;
  private ?string $created_at;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public static function attachGenres(int $movieId, array $genreIds): void {
    $db = static::$db;
    $stmt = $db->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");

    foreach ($genreIds as $genreId) {
      $stmt->bind_param("ii", $movieId, $genreId);
      $stmt->execute();
    }
  }

  public function getGenres(): array {
    $db = static::$db;
    $stmt = $db->prepare("
      SELECT g.id, g.name
      FROM genres g
      JOIN movie_genres mg ON g.id = mg.genre_id
      WHERE mg.movie_id = ?
    ");
    $stmt->bind_param("i", $this->id);
    $stmt->execute();
    $result = $stmt->get_result();

    $genres = [];
    while ($row = $result->fetch_assoc()) {
      $genres[] = $row;
    }

    return $genres;
  }

  public function getShowtimes(): array {
    $db = static::$db;
    $stmt = $db->prepare("SELECT * FROM showtimes WHERE movie_id = ?");
    $stmt->bind_param("i", $this->id);
    $stmt->execute();

    $result = $stmt->get_result();
    $showtimes = [];
    while ($row = $result->fetch_assoc()) {
      $showtimes[] = new Showtime($row);
    }

    return $showtimes;
  }

  public function toArray(): array {
    $data = get_object_vars($this);
    $data['genres'] = $this->getGenres();
    $data['showtimes'] = array_map(fn($s) => $s->toArray(), $this->getShowtimes());
    return $data;
  }
}
