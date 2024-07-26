<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/Task.php';

$database = new Database();
$db = $database->getConnection();
$task = new Task($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        $stmt = $task->read();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input")); 

        if (is_array($data)) {
           
            foreach ($data as $item) {
                if(isset($item->project_id) && isset($item->user_id) && isset($item->name) && isset($item->description)) {
                    $task->project_id = $item->project_id;
                    $task->user_id = $item->user_id;
                    $task->name = $item->name;
                    $task->description = $item->description;

                    if(!$task->create()) {
                        echo json_encode(["message" => "Unable to create task for project_id " . $item->project_id]);
                        exit;
                    }
                } else {
                    echo json_encode(["message" => "Incomplete data provided for project_id " . $item->project_id]);
                    exit;
                }
            }
            echo json_encode(["message" => "Tasks were created."]);
        } else {
           
            if(isset($data->project_id) && isset($data->user_id) && isset($data->name) && isset($data->description)) {
                $task->project_id = $data->project_id;
                $task->user_id = $data->user_id;
                $task->name = $data->name;
                $task->description = $data->description;

                if($task->create()) {
                    echo json_encode(["message" => "Task was created."]);
                } else {
                    echo json_encode(["message" => "Unable to create task."]);
                }
            } else {
                echo json_encode(["message" => "Incomplete data provided."]);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input")); 

        if(isset($data->id) && isset($data->project_id) && isset($data->user_id) && isset($data->name) && isset($data->description)) {
            $task->id = $data->id;
            $task->project_id = $data->project_id;
            $task->user_id = $data->user_id;
            $task->name = $data->name;
            $task->description = $data->description;

            if($task->update()) {
                echo json_encode(["message" => "Task was updated."]);
            } else {
                echo json_encode(["message" => "Unable to update task."]);
            }
        } else {
            echo json_encode(["message" => "Incomplete data provided."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input")); 

        if(isset($data->id)) {
            $task->id = $data->id;
            if($task->delete()) {
                echo json_encode(["message" => "Task was deleted."]);
            } else {
                echo json_encode(["message" => "Unable to delete task."]);
            }
        } else {
            echo json_encode(["message" => "Incomplete data provided."]);
        }
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
