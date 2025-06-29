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
}
