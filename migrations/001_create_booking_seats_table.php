<?php
require("../connection/connection.php");

$query = "CREATE TABLE booking_seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    seat_id INT NOT NULL,
    showtime_id INT NOT NULL,
    price DECIMAL(6, 2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE (seat_id, showtime_id),

    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (seat_id) REFERENCES seats(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
);";

if ($mysqli->query($query)) {
  echo "Table `booking_seats` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
