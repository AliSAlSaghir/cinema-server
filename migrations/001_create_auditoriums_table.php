<?php
require("../connection/connection.php");

$query = "CREATE TABLE auditoriums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,  
    seat_rows INT NOT NULL,            
    seats_per_row INT NOT NULL
);
";

if ($mysqli->query($query)) {
  echo "Table `auditoriums` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
