<?php
require("../connection/connection.php");

$query = "CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    showtime_id INT NOT NULL,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(8, 2) DEFAULT 0.00,
    coupon_code VARCHAR(50),
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    payment_method ENUM('online', 'on_site') DEFAULT 'online',

    FOREIGN KEY (coupon_code) REFERENCES coupons(code) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (showtime_id) REFERENCES showtimes(id) ON DELETE CASCADE
);";

if ($mysqli->query($query)) {
  echo "Table `bookings` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
