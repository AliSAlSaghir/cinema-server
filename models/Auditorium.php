<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Showtime.php';

class Auditorium extends Model {
  protected static string $table = 'auditoriums';

  private int $id;
  private string $name;
  private int $seat_rows;
  private int $seats_per_row;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }


  public function getSeatCount(): int {
    return $this->seat_rows * $this->seats_per_row;
  }

  public function generateSeatLabels(): array {
    $labels = [];

    for ($row = 0; $row < $this->seat_rows; $row++) {
      $rowLabel = chr(65 + $row);
      $rowSeats = [];

      for ($num = 1; $num <= $this->seats_per_row; $num++) {
        $rowSeats[] = $rowLabel . $num;
      }

      $labels[] = $rowSeats;
    }

    return $labels;
  }


  public function generateSeatEntities(): array {
    $seats = [];

    for ($row = 0; $row < $this->seat_rows; $row++) {
      $rowLabel = chr(65 + $row);

      for ($num = 1; $num <= $this->seats_per_row; $num++) {
        $seats[] = [
          'auditorium_id' => $this->id,
          'row_label' => $rowLabel,
          'seat_number' => $num
        ];
      }
    }

    return $seats;
  }

  public function persistSeats(): void {
    $db = static::$db;
    $stmt = $db->prepare("INSERT INTO seats (auditorium_id, row_label, seat_number) VALUES (?, ?, ?)");

    foreach ($this->generateSeatEntities() as $seat) {
      $stmt->bind_param("isi", $seat['auditorium_id'], $seat['row_label'], $seat['seat_number']);
      $stmt->execute();
    }

    $stmt->close();
  }

  public function getShowtimes(): array {
    $db = static::$db;
    $stmt = $db->prepare("SELECT * FROM showtimes WHERE auditorium_id = ?");
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
    return [
      'id' => $this->id,
      'name' => $this->name,
      'seat_rows' => $this->seat_rows,
      'seats_per_row' => $this->seats_per_row,
      'total_seats' => $this->getSeatCount(),
      'seat_labels' => $this->generateSeatLabels(),
      'showtimes' => array_map(fn($s) => $s->toArray(), $this->getShowtimes())
    ];
  }
}
