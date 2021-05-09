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

$admin_key =  'GJ5TY6G8IJ56HH87876JFJFT7HFFF';
if(!empty($request_data->key)){
    if($request_data->key !== $admin_key){
        // set response code - 400 bad request
        http_response_code(400);
        // tell the user
        echo json_encode(array("message" => "You haven't permission to access."));
        exit;
    }

    // make sure data is not empty
    if(!empty($request_data->old_account) && !empty($request_data->account_no) && !empty($request_data->user_name) && !empty($request_data->version) && !empty($request_data->latest_version) ){
        
        $old_account = filter_var($request_data->old_account, FILTER_VALIDATE_INT);
        $user_name = filter_var($request_data->user_name, FILTER_SANITIZE_STRING);
        $account_no = filter_var($request_data->account_no, FILTER_VALIDATE_INT);
        $version = filter_var($request_data->version, FILTER_VALIDATE_INT);
        $latest_version = filter_var($request_data->latest_version, FILTER_VALIDATE_INT);

        $users->old_account = $old_account;
        $users->user_name = $user_name;
        $users->account_number = $account_no;
        $users->version = $version;
        $users->latestVersion = $latest_version;

        // create the users
        if($users->update()){
    
            // set response code - 201 created
            http_response_code(201);
    
            // tell the user
            echo json_encode(array("message" => "Data Updated."));
        }
    
        // if unable to create the users, tell the user
        else{
    
            // set response code - 503 service unavailable
            http_response_code(503);
    
            // tell the user
            echo json_encode(array("message" => "Unable to Updated."));
        }
    }
    // tell the user data is incomplete
    else{
    
        // set response code - 400 bad request
        http_response_code(400);
    
        // tell the user
        echo json_encode(array("message" => "Unable to update data. rquire data is incomplete."));
    }
}