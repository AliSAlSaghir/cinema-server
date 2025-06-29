<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/User.php';

Model::setDB($mysqli);

$users = [
  [
    'name'                     => 'Ali Al Saghir',
    'email'                    => 'ali@example.com',
    'phone_number'             => '70000001',
    'password'                 => password_hash('secret123', PASSWORD_DEFAULT),
    'date_of_birth'            => '2000-01-01',
    'national_id_image'        => '/uploads/national_ids/sample1.jpeg',
    'profile_picture'          => '/uploads/profile_pictures/sample1.jpeg',
    'preferred_day'            => 'Friday',
    'preferred_time'           => '20:00:00',
    'preferred_payment_method' => 'online',
    'communication_preference' => 'email',
    'is_admin'                 => true,
    'membership'               => 'gold',
    'created_at'               => date('Y-m-d H:i:s')
  ],
  [
    'name'                     => 'Charbel Farhat',
    'email'                    => 'charbel@example.com',
    'phone_number'             => '70000002',
    'password'                 => password_hash('mypassword', PASSWORD_DEFAULT),
    'date_of_birth'            => '2010-05-20',
    'national_id_image'        => '/uploads/national_ids/sample2.jpeg',
    'profile_picture'          => '/uploads/profile_pictures/sample2.jpeg',
    'preferred_day'            => 'Sunday',
    'preferred_time'           => '16:00:00',
    'preferred_payment_method' => 'on_site',
    'communication_preference' => 'sms',
    'is_admin'                 => false,
    'membership'               => 'silver',
    'created_at'               => date('Y-m-d H:i:s')
  ]
];

foreach ($users as $userData) {
  $user = User::create($userData);

  if ($user) {
    echo "Seeded user: " . $userData['name'] . PHP_EOL;
  } else {
    echo "Failed to seed user: " . $userData['name'] . PHP_EOL;
  }
}
