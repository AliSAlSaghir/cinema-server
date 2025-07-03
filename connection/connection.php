<?php

$db_host = "localhost";
$db_name = "cinema-db";
$db_user = "root";
$db_pass = null;

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_error()) {
  error_log("Database connection failed: " . mysqli_connect_error());
  die("Could not connect to database. Please try again later.");
}
