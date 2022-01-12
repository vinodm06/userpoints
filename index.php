<?php
error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
header('Access-Control-Allow-Origin: https://indriyam.com');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
include_once "config/Database.php";
include_once "config/HttpStatusCode.php";
include_once "Models/User.php";
include_once "Controllers/UserController.php";

// Get request method, query and inputs
$method = $_SERVER['REQUEST_METHOD'];
$q = $_GET['q'];
$route = explode("/", $q);
$controller = $route[0];
$id = (int) $route[1];
$action = $route[2];
$userData = json_decode(file_get_contents("php://input")); 

$userController = new UserController();

// Route to process
if(!$id && $method === "GET"){

  $userController->getUsers();
} else if (in_array($action, array("increment", "decrement")) && $method === "PATCH"){
  $userController->updatePoints($id, $action, $userData);
} else {
  switch($method){
    case "PATCH":
    case "PUT":
      $userController->updateUser($userData);
      break;
    case "POST":
      $userController->createUser($userData);
      break;
    case "DELETE":
      $userController->deleteUser($id);
      break;
    case "GET":
      $userController->getUser($id);
      break;
    case "OPTIONS":
      break; 
    default:
      http_response_code(HttpStatusCode::HTTP_METHOD_NOT_ALLOWED);
      break;
  }
}

?>



