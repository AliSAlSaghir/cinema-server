<?php
require("../connection/connection.php");

$query = "CREATE TABLE showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT NOT NULL,
    auditorium_id INT NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (movie_id, auditorium_id, show_date, show_time),

    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (auditorium_id) REFERENCES auditoriums(id) ON DELETE CASCADE
);
";

if ($mysqli->query($query)) {
  echo "Table `showtimes` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
