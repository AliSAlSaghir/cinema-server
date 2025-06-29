<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';
Model::setDB($mysqli);

// Sample snacks
$snacks = [
  [
    'name' => 'Popcorn (Large)',
    'price' => 5.00,
    'image' => '/uploads/snacks/popcorn_large.jpeg',
    'is_available' => 1
  ],
  [
    'name' => 'Popcorn (Small)',
    'price' => 3.00,
    'image' => '/uploads/snacks/popcorn_small.jpeg',
    'is_available' => 1
  ],
  [
    'name' => 'Soft Drink',
    'price' => 2.50,
    'image' => '/uploads/snacks/soft_drink.jpeg',
    'is_available' => 1
  ],
  [
    'name' => 'Nachos with Cheese',
    'price' => 4.50,
    'image' => '/uploads/snacks/nachos.jpeg',
    'is_available' => 1
  ],
  [
    'name' => 'Chocolate Bar',
    'price' => 1.75,
    'image' => '/uploads/snacks/chocolate.jpeg',
    'is_available' => 1
  ],
  [
    'name' => 'Water Bottle',
    'price' => 1.00,
    'image' => '/uploads/snacks/water.jpeg',
    'is_available' => 1
  ],
];

foreach ($snacks as $snack) {
  $stmt = $mysqli->prepare("INSERT INTO snacks (name, price, image, is_available) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sdsi", $snack['name'], $snack['price'], $snack['image'], $snack['is_available']);

  if ($stmt->execute()) {
    echo "✅ Seeded snack: {$snack['name']}" . PHP_EOL;
  } else {
    echo "❌ Failed to seed snack: {$snack['name']} - " . $stmt->error . PHP_EOL;
  }

  $stmt->close();
}
