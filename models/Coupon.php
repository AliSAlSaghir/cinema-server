<?php

require_once __DIR__ . '/Model.php';

class Coupon extends Model {
  protected static string $table = 'coupons';

  private int $id;
  private string $code;
  private float $discount_percentage;
  private ?string $expires_at;
  private bool $is_active;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public function toArray(): array {
    return [
      'id' => $this->id,
      'code' => $this->code,
      'discount_percentage' => $this->discount_percentage,
      'expires_at' => $this->expires_at,
      'is_active' => $this->is_active
    ];
  }

  public static function getValidCoupon(string $code): ?Coupon {
    $db = static::$db;
    $stmt = $db->prepare("SELECT * FROM coupons WHERE code = ? AND is_active = 1 AND (expires_at IS NULL OR expires_at > NOW()) LIMIT 1");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
      return new Coupon($row);
    }

    return null;
  }


  public function calculateDiscount(float $total): float {
    return round(($this->discount_percentage / 100.0) * $total, 2);
  }
}
