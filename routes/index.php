<?php
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$request_method = $_SERVER['REQUEST_METHOD'];

$api_endpoints = [
    'users' => '../controllers/UserController.php',
    'tasks' => '../controllers/TaskController.php',
    'projects' => '../controllers/ProjectController.php'
];

foreach ($api_endpoints as $endpoint => $controller) {
    if (strpos($request_uri, $endpoint) !== false) {
        include_once($controller);
        break;
    }
}
?>
