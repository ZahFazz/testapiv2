<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/project.php';

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        $stmt = $project->read();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($projects);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (is_array($data)) {
            $responses = [];

            foreach ($data as $item) {
                $project->name = $item->name;
                $project->description = $item->description;

                if ($project->create()) {
                    $responses[] = ["name" => $item->name, "message" => "Project was created."];
                } else {
                    $responses[] = ["name" => $item->name, "message" => "Unable to create project."];
                }
            }

            echo json_encode($responses);
        } else {
            echo json_encode(["message" => "Invalid input format. Please provide an array of projects."]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $project->id = $data->id;
        $project->name = $data->name;
        $project->description = $data->description;
        if($project->update()) {
            echo json_encode(["message" => "Project was updated."]);
        } else {
            echo json_encode(["message" => "Unable to update project."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $project->id = $data->id;
        if($project->delete()) {
            echo json_encode(["message" => "Project was deleted."]);
        } else {
            echo json_encode(["message" => "Unable to delete project."]);
        }
        break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
