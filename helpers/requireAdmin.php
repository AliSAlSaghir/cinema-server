<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/response.php';

function requireAdmin($userId) {
  if (!$userId) {
    respond(401, ['error' => 'User ID is required']);
  }

  $isAdmin = User::isAdmin($userId);

  if ($isAdmin === null) {
    respond(404, ['error' => 'User not found']);
  }

  if (!$isAdmin) {
    respond(403, ['error' => 'Only admins can perform this action']);
  }
}
