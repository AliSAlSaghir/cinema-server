<?php
require("../connection/connection.php");

$query = "CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) DEFAULT NULL UNIQUE,
  phone_number VARCHAR(20) DEFAULT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  date_of_birth DATE DEFAULT NULL,
  national_id_image VARCHAR(255) DEFAULT NULL,
  profile_picture VARCHAR(255) DEFAULT NULL,
  preferred_day ENUM('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') DEFAULT NULL,
  preferred_time TIME DEFAULT NULL,
  preferred_payment_method ENUM('on_site', 'online') DEFAULT 'on_site',
  communication_preference ENUM('email', 'sms') DEFAULT 'email',
  is_admin BOOLEAN DEFAULT 0,
  membership ENUM('none', 'silver', 'gold', 'platinum') DEFAULT 'none',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

";

if ($mysqli->query($query)) {
  echo "Table `user` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
