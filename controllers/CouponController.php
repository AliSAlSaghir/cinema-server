<?php

require_once __DIR__ . '/../models/Coupon.php';
require_once __DIR__ . '/BaseController.php';

class CouponController  extends BaseController {

  public function get_coupons() {
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
  }

  public function create_coupon() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
      respond(400, ['error' => 'Invalid JSON input']);
    }

    requireAdmin($input['user_id'] ?? null);

    $code           = $input['code'] ?? null;
    $discountPercentage  = $input['discount_percentage'] ?? null;
    $expiresAt      = $input['expires_at'] ?? null;
    $isActive       = $input['is_active'] ?? true;

    if (!$code || !$discountPercentage) {
      respond(400, ['error' => 'Code and discount_percentage are required']);
    }

    $data = [
      'code' => $code,
      'discount_percentage' => $discountPercentage,
      'expires_at' => $expiresAt,
      'is_active' => (bool)$isActive
    ];

    $coupon = Coupon::create($data);

    if ($coupon) {
      respond(200, [
        'message' => 'Coupon created successfully',
        'coupon' => $coupon->toArray()
      ]);
    } else {
      respond(500, ['error' => 'Failed to create coupon']);
    }
  }
}
