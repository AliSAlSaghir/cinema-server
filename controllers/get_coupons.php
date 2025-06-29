<?php
require_once __DIR__ . '/../helpers/allowCORS.php';

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/../helpers/response.php';

Model::setDB($mysqli);

$couponId = isset($_GET['id']) ? intval($_GET['id']) : null;
$filterActive = isset($_GET['is_active']);

if ($couponId) {
  $coupon = Coupon::find($couponId);

  if (!$coupon) {
    respond(404, ['error' => 'Coupon not found']);
  }

  respond(200, ['coupon' => $coupon->toArray()]);
} else {
  $coupons = Coupon::all();

  if ($filterActive) {
    $coupons = array_filter($coupons, fn($c) => $c->toArray()['is_active'] === true);
  }

  $result = array_map(fn($c) => $c->toArray(), $coupons);

  respond(200, ['coupons' => array_values($result)]);
}
