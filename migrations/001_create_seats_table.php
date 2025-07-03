<?php
require("../connection/connection.php");

$query = "CREATE TABLE seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    auditorium_id INT NOT NULL,
    row_label VARCHAR(5) NOT NULL,
    seat_number INT NOT NULL,

    FOREIGN KEY (auditorium_id) REFERENCES auditoriums(id) ON DELETE CASCADE
);
";

if ($mysqli->query($query)) {
  echo "Table `seats` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
