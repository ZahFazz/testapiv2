<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/User.php';
include_once '../helpers/JwtHelper.php';
include_once '../middleware/AuthMiddleware.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        $stmt = $user->read();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;


    
    // case 'POST':
    //     if (strpos($_SERVER['REQUEST_URI'], 'login') !== false) {
    //         $data = json_decode(file_get_contents("php://input"));
    //         $user->email = $data->email;
    //         $user->password = $data->password;
    //         if ($user_data = $user->login()) {
    //             $token = JwtHelper::generateToken(['id' => $user_data['id'], 'email' => $user_data['email']]);
    //             echo json_encode(["token" => $token]);
    //         } else {
    //             echo json_encode(["message" => "Invalid credentials."]);
    //         }
    //     } else {
    //         $data = json_decode(file_get_contents("php://input"));
    //         $user->name = $data->name;
    //         $user->email = $data->email;
    //         $user->avatar = $data->avatar;
    //         if($user->create()) {
    //             echo json_encode(["message" => "User was created."]);
    //         } else {
    //             echo json_encode(["message" => "Unable to create user."]);
    //         }
    //     }
    //     break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true); // Decode JSON as an associative array
        if (isset($data[0])) { // Check if it's an array of users
            $response = [];
            foreach ($data as $userData) {
                $user->name = $userData['name'];
                $user->email = $userData['email'];
                $user->avatar = $userData['avatar'];
                if($user->create()) {
                    $response[] = ["message" => "User " . $user->name . " was created."];
                } else {
                        $response[] = ["message" => "Unable to create user " . $user->name . "."];
                }
            }
            echo json_encode($response);
        } else {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->avatar = $data['avatar'];
            if($user->create()) {
                echo json_encode(["message" => "User was created."]);
            } else {
                echo json_encode(["message" => "Unable to create user."]);
            }
        }
        break;

   case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true); // Decode JSON as associative array
        $user->id = $data['id'] ?? null;
        $user->name = $data['name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->avatar = $data['avatar'] ?? null;
        if ($user->update()) {
            echo json_encode(["message" => "User was updated."]);
        } else {
            echo json_encode(["message" => "Unable to update user."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $user->id = $data->id;
        if($user->delete()) {
            echo json_encode(["message" => "User was deleted."]);
        } else {
            echo json_encode(["message" => "Unable to delete user."]);
        }
        break;
}

?>
