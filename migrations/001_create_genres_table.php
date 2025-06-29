<?php
require("../connection/connection.php");

$query = "CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);";

if ($mysqli->query($query)) {
  echo "Table `genres` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
