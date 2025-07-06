<?php

$apis = [
  '/login'         => ['controller' => 'AuthController', 'method' => 'login'],
  '/register'         => ['controller' => 'AuthController', 'method' => 'register'],

  '/update_user' => ['controller' => 'UserController', 'method' => 'update_user'],
  '/get_users' => ['controller' => 'UserController', 'method' => 'get_users'],

  '/get_movies'         => ['controller' => 'MovieController', 'method' => 'get_movies'],
  '/create_movie'         => ['controller' => 'MovieController', 'method' => 'create_movie'],

  '/get_coupons'         => ['controller' => 'CouponController', 'method' => 'get_coupons'],
  '/create_coupon'         => ['controller' => 'CouponController', 'method' => 'create_coupon'],

  '/get_genres'         => ['controller' => 'GenreController', 'method' => 'get_genres'],
  '/create_genre'         => ['controller' => 'GenreController', 'method' => 'create_genre'],

  '/get_auditoriums'         => ['controller' => 'AuditoriumController', 'method' => 'get_auditoriums'],
  '/create_auditorium'         => ['controller' => 'AuditoriumController', 'method' => 'create_auditorium'],

  '/get_showtimes'         => ['controller' => 'ShowtimeController', 'method' => 'get_showtimes'],
  '/create_showtime'         => ['controller' => 'ShowtimeController', 'method' => 'create_showtime'],

  '/get_snacks'         => ['controller' => 'SnackController', 'method' => 'get_snacks'],
  '/create_snack'         => ['controller' => 'SnackController', 'method' => 'create_snack'],

  '/get_bookings'         => ['controller' => 'BookingController', 'method' => 'get_bookings'],
  '/create_booking'         => ['controller' => 'BookingController', 'method' => 'create_booking'],

  '/get_seats'         => ['controller' => 'SeatController', 'method' => 'get_seats'],
];
