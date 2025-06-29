<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/response.php';

function requireAuth(?int $userId): void {
  if (!$userId) {
    respond(401, ['error' => 'User ID is required']);
  }

  $user = User::find($userId);

  if (!$user) {
    respond(404, ['error' => 'User not found']);
  }
}
