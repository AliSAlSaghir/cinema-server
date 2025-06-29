<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';

Model::setDB($mysqli);

$coupons = [
  [
    'code' => 'WELCOME10',
    'discount_percentage' => 10.0,
    'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
    'is_active' => 1
  ],
  [
    'code' => 'SUMMER20',
    'discount_percentage' => 20.0,
    'expires_at' => date('Y-m-d H:i:s', strtotime('+10 days')),
    'is_active' => 1
  ],
  [
    'code' => 'EXPIRED50',
    'discount_percentage' => 50.0,
    'expires_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
    'is_active' => 0
  ],
  [
    'code' => 'VIP30',
    'discount_percentage' => 30.0,
    'expires_at' => date('Y-m-d H:i:s', strtotime('+60 days')),
    'is_active' => 1
  ],
  [
    'code' => 'WEEKEND5',
    'discount_percentage' => 5.0,
    'expires_at' => date('Y-m-d H:i:s', strtotime('+5 days')),
    'is_active' => 1
  ]
];

foreach ($coupons as $c) {
  $stmt = $mysqli->prepare("INSERT INTO coupons (code, discount_percentage, expires_at, is_active) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sdsi", $c['code'], $c['discount_percentage'], $c['expires_at'], $c['is_active']);

  if ($stmt->execute()) {
    echo "✅ Coupon '{$c['code']}' seeded successfully\n";
  } else {
    echo "❌ Failed to seed coupon '{$c['code']}': " . $stmt->error . "\n";
  }

  $stmt->close();
}
