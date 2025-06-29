<?php
require("../connection/connection.php");

$query = "CREATE TABLE snacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(6, 2) NOT NULL,
    image TEXT,
    is_available BOOLEAN DEFAULT TRUE
);
";

if ($mysqli->query($query)) {
  echo "Table `snacks` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
