<?php

require_once __DIR__ . '/../connection/connection.php';
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../helpers/allowCORS.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/requireAdmin.php';

abstract class BaseController {
  public function __construct() {
    global $mysqli;
    Model::setDB($mysqli);
  }
}
