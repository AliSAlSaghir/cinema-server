<?php

require_once 'Model.php';

class User extends Model {
  protected static string $table = "users";

  private int $id;
  private ?string $name;
  private ?string $email;
  private ?string $phone_number;
  private ?string $password;
  private ?string $date_of_birth;
  private ?string $national_id_image;
  private ?string $profile_picture;
  private ?string $preferred_day;
  private ?string $preferred_time;
  private ?string $preferred_payment_method;
  private ?string $communication_preference;
  private int $is_admin;
  private ?string $membership;
  private ?string $created_at;

  public function __construct(array $data) {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->$key = $value;
      }
    }
  }

  public static function getByEmailOrPhone(string $input) {
    $sql = "SELECT * FROM users WHERE email = ? OR phone_number = ?";
    $stmt = static::$db->prepare($sql);
    if (!$stmt) return null;

    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    return $data ? new static($data) : null;
  }

  public static function isAdmin($userId) {
    $db = static::$db;

    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null; // User not found
    }

    $user = $result->fetch_assoc();
    return (bool)$user['is_admin'];
  }



  public function getId(): int {
    return $this->id;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function getIsAdmin(): int {
    return $this->is_admin;
  }

  public function setName(string $name): void {
    $this->name = $name;
  }

  public function setEmail(?string $email): void {
    $this->email = $email;
  }

  public function setPhoneNumber(?string $phone): void {
    $this->phone_number = $phone;
  }

  public function setPassword(string $password): void {
    $this->password = password_hash($password, PASSWORD_DEFAULT);
  }

  public function setDateOfBirth(?string $date): void {
    $this->date_of_birth = $date;
  }

  public function setNationalIdImage(?string $path): void {
    $this->national_id_image = $path;
  }

  public function setProfilePicture(?string $path): void {
    $this->profile_picture = $path;
  }

  public function setPreferredDay(?string $day): void {
    $this->preferred_day = $day;
  }

  public function setPreferredTime(?string $time): void {
    $this->preferred_time = $time;
  }

  public function setPreferredPaymentMethod(?string $method): void {
    $this->preferred_payment_method = $method;
  }

  public function setCommunicationPreference(?string $preference): void {
    $this->communication_preference = $preference;
  }

  public function setIsAdmin(int $isAdmin): void {
    $this->is_admin = $isAdmin;
  }

  public function setMembership(?string $membership): void {
    $this->membership = $membership;
  }

  public function setCreatedAt(string $createdAt): void {
    $this->created_at = $createdAt;
  }
  public function getMembership(): string {
    return $this->membership;
  }

  public function toArray(): array {
    $data = get_object_vars($this);
    unset($data['password']);
    return $data;
  }
}
