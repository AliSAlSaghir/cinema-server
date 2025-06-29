<?php
require("../connection/connection.php");

$query = "CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    release_date DATE,
    duration_minutes INT,
    rating ENUM('G', 'PG', 'PG-13', 'R', 'NC-17', 'NR') DEFAULT 'NR',
    poster TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

if ($mysqli->query($query)) {
  echo "Table `movies` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
