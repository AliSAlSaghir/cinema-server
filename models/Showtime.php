<?php

require_once __DIR__ . '/Model.php';

class Showtime extends Model {
  protected static string $table = 'showtimes';

  private int $id;
  private int $movie_id;
  private int $auditorium_id;
  private string $show_date;
  private string $show_time;
  private ?string $created_at;
  private ?string $movie_title = null;
  private ?string $auditorium_name = null;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public static function exists(int $id): bool {
    $db = static::$db;
    $stmt = $db->prepare("SELECT id FROM showtimes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
  }

  public static function filter(array $filters): array {
    $db = static::$db;

    $sql = "
      SELECT 
        s.*, 
        m.title AS movie_title, 
        a.name AS auditorium_name
      FROM showtimes s
      JOIN movies m ON s.movie_id = m.id
      JOIN auditoriums a ON s.auditorium_id = a.id
      WHERE 1 = 1
    ";

    $params = [];
    $types = '';

    if (!empty($filters['id'])) {
      $sql .= " AND s.id = ?";
      $params[] = $filters['id'];
      $types .= 'i';
    }

    if (!empty($filters['movie_id'])) {
      $sql .= " AND s.movie_id = ?";
      $params[] = $filters['movie_id'];
      $types .= 'i';
    }

    if (!empty($filters['auditorium_id'])) {
      $sql .= " AND s.auditorium_id = ?";
      $params[] = $filters['auditorium_id'];
      $types .= 'i';
    }

    $stmt = $db->prepare($sql);
    if ($params) {
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $showtimes = [];
    while ($row = $result->fetch_assoc()) {
      $showtime = new self($row);
      $showtime->movie_title = $row['movie_title'];
      $showtime->auditorium_name = $row['auditorium_name'];
      $showtimes[] = $showtime;
    }

    return $showtimes;
  }

  public function toArray(): array {
    return array_merge(get_object_vars($this), [
      'movie_title' => $this->movie_title ?? null,
      'auditorium_name' => $this->auditorium_name ?? null,
    ]);
  }
}
