<?php
require(__DIR__ . '/connection/connection.php');
require(__DIR__ . '/routes/api.php');

// This block is used to extract the route name from the URL
//----------------------------------------------------------
// Define your base directory 
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base directory from the request if present
if (strpos($request, $base_dir) === 0) {
  $request = substr($request, strlen($base_dir));
}

// Ensure the request is at least '/'
if ($request == '') {
  $request = '/';
}

//Examples: 
//http://localhost/getArticles -------> $request = "getArticles"
//http://localhost/ -------> $request = "/" (why? because of the if)

// This block is used to extract the route name from the URL
//----------------------------------------------------------


//Routing starts here (Mapping between the request and the controller & method names)
//It's an key-value array where the value is an key-value array
//----------------------------------------------------------


//----------------------------------------------------------


//Routing Logic here 
//This is a dynamic logic, that works on any array... 
//----------------------------------------------------------
if (isset($apis[$request])) {
  $controller_name = $apis[$request]['controller']; //if $request == /articles, then the $controller_name will be "ArticleController" 
  $method = $apis[$request]['method'];
  require_once "controllers/{$controller_name}.php";

  $controller = new $controller_name();
  if (method_exists($controller, $method)) {
    $controller->$method();
  } else {
    echo "Error: Method {$method} not found in {$controller_name}.";
  }
} else {
  echo "404 Not Found";
}
