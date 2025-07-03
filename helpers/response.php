<?php

function respond(int $code, array $data) {
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode(['status' => $code] + $data);
  exit;
}
