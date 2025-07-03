<?php
require("../connection/connection.php");

$query = "CREATE TABLE booking_snacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    snack_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price_per_unit DECIMAL(6,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (booking_id, snack_id),

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (snack_id) REFERENCES snacks(id) ON DELETE CASCADE
);";

if ($mysqli->query($query)) {
  echo "Table `booking_snacks` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
