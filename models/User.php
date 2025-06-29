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


  public function toArray(): array {
    return [
      'id'                         => $this->id,
      'name'                       => $this->name,
      'email'                      => $this->email,
      'phone_number'               => $this->phone_number,
      'date_of_birth'              => $this->date_of_birth,
      'national_id_image'     => $this->national_id_image,
      'profile_picture'       => $this->profile_picture,
      'preferred_day'              => $this->preferred_day,
      'preferred_time'             => $this->preferred_time,
      'preferred_payment_method'   => $this->preferred_payment_method,
      'communication_preference'   => $this->communication_preference,
      'is_admin'                   => $this->is_admin,
      'membership'                 => $this->membership,
      'created_at'                 => $this->created_at
    ];
  }
}
