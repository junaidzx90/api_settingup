<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once 'objects/users.php';

$database = new Database();
$db = $database->getConnection();
$users = new Users($db);

// get posted data
$request_data = json_decode(file_get_contents("php://input"));

$admin_key =  "GJ5TY6G8IJ56HH87876JFJFT7HFFF";
if(!empty($request_data->key)){
    if($request_data->key !== $admin_key){
        // set response code - 400 bad request
        http_response_code(400);
        // tell the user
        echo json_encode(array("message" => "You haven't permission to access."));
        exit;
    }

    // make sure data is not empty
    if(!empty($request_data->account_no) && !empty($request_data->user_name) && !empty($request_data->version) && !empty($request_data->latest_version) ){

        $user_name = filter_var($request_data->user_name, FILTER_SANITIZE_STRING);
        $account_no = filter_var($request_data->account_no, FILTER_VALIDATE_INT);
        $old_account = filter_var($request_data->old_account, FILTER_VALIDATE_INT);
        $version = $request_data->version;
        $latest_version = $request_data->latest_version;

        $users->user_name = $user_name;
        $users->account_number = $account_no;
        $users->old_account = $old_account;
        $users->version = $version;
        $users->latestVersion = $latest_version;

        // create the users
        if($users->create()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Data Inserted."));
        }
    
        // if unable to create the users, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to Insert."));
        }
    }
    // tell the user data is incomplete
    else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to insert data. rquire data is incomplete."));
    }
}