<?php
require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../helpers/allowCORS.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../models/Booking.php';

Model::setDB($mysqli);

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;

$bookings = Booking::getAllWithDetails($userId);

respond(200, ['bookings' => array_map(fn($b) => $b->toArray(), $bookings)]);
