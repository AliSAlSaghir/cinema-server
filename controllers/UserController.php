<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/BaseController.php';

class UserController extends BaseController {

  public function get_users() {

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if ($id) {
      $user = User::find($id);
      if (!$user) {
        respond(404, ['error' => 'User not found']);
      }
      respond(200, ['user' => $user->toArray()]);
    }

    $users = User::all();

    $data = array_map(fn($a) => $a->toArray(), $users);
    respond(200, ['users' => $data]);
  }

  public function update_user() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $input = str_contains($contentType, 'application/json')
      ? json_decode(file_get_contents('php://input'), true)
      : $_POST;

    $id = $input['id'] ?? null;
    if (!$id || !is_numeric($id)) {
      respond(400, ['error' => 'Missing or invalid user ID']);
    }

    $user = User::find((int)$id);
    if (!$user) {
      respond(404, ['error' => 'User not found']);
    }

    if (isset($input['name'])) $user->setName($input['name']);
    if (isset($input['email'])) $user->setEmail($input['email']);
    if (isset($input['phone_number'])) $user->setPhoneNumber($input['phone_number']);
    if (isset($input['date_of_birth'])) $user->setDateOfBirth($input['date_of_birth']);
    if (isset($input['preferred_day'])) $user->setPreferredDay($input['preferred_day']);
    if (isset($input['preferred_time'])) $user->setPreferredTime($input['preferred_time']);
    if (isset($input['preferred_payment_method'])) $user->setPreferredPaymentMethod($input['preferred_payment_method']);
    if (isset($input['communication_preference'])) $user->setCommunicationPreference($input['communication_preference']);
    if (isset($input['membership'])) $user->setMembership($input['membership']);
    if (isset($input['password'])) $user->setPassword($input['password']);

    $nid = $this->handleUpload('national_id_image', 'nid_');
    $pic = $this->handleUpload('profile_picture', 'pp_');

    if ($nid) $user->setNationalIdImage($nid);
    if ($pic) $user->setProfilePicture($pic);

    if ($user->update()) {
      respond(200, ['message' => 'User updated successfully']);
    } else {
      respond(500, ['error' => 'Failed to update user']);
    }
  }

  private function handleUpload($fileKey, $prefix = 'upload_') {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
      $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
      $filename = uniqid($prefix) . '.' . $ext;

      $subFolder = match ($fileKey) {
        'national_id_image' => 'national_ids',
        'profile_picture'   => 'profile_pictures',
        default             => 'others'
      };

      $uploadDir = __DIR__ . "/../uploads/$subFolder/";
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

      move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $filename);
      return "/uploads/$subFolder/" . $filename;
    }

    return null;
  }
}
