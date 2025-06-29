<?php
require("../connection/connection.php");

$query = "CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_percentage FLOAT NOT NULL,
    expires_at DATETIME,
    is_active BOOLEAN DEFAULT TRUE
);

";

if ($mysqli->query($query)) {
  echo "Table `coupons` created successfully.";
} else {
  echo "Error creating table: " . $mysqli->error;
}
