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

  public function toArray(): array {
    return array_merge(get_object_vars($this), [
      'movie_title' => $this->movie_title ?? null,
      'auditorium_name' => $this->auditorium_name ?? null,
    ]);
  }
}
