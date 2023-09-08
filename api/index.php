<?php

// github url(s)
$github_file_url = "https://github.com/realthonie/Zuri_HNGX_Backend_1/index.php";
$github_repo_url = "https://github.com/realthonie/Zuri_HNGX_Backend_1";


$return = false;
$status_code = 404;
$status_massage = 'Bad Request. Failed!';
// Checking is the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $return = false;
    $status_code = 400;
    $status_massage = 'The request URL should include two parameter named slack_name  and track';
    // Checking if the two get parameter are included in the request url
    if(isset($_GET['slack_name']) && isset($_GET['track'])){
        
        $slack_name = $_GET['slack_name'];
        $track = $_GET['track'];
        
        $return = false;
        $status_code = 400;
        $status_massage = 'The two parameters passed should have at least 1 character as a value each';
        // Checking if any of the two parameters does not have a value greater than 0 character
        if(strlen($slack_name) > 0 && strlen($track) > 0){
            $return = true;
            $status_code = 200;
            $status_massage = '';
        }
    }
}

// Adding validation function for date
function date_validation($date, $format){
    $date_created = DateTime::createFromFormat($format, $date);
    return $date_created && $date_created->format($format) == $date;
}

// Adding response function for API
function response($slack_name, $track, $github_file_url, $github_repo_url){
    // validating the date
    $utc_time = (date_validation(gmdate("Y-m-d\TH:i:s\Z"), "Y-m-d\TH:i:s\Z") === true)?gmdate("Y-m-d\TH:i:s\Z"):'Date Validation failed';
    $current_day = (date_validation(gmdate("l"), 'l') === true)?gmdate("l"):'Day Validation failed';

    $status_code = 200;
    // Creating the response array
    $response = array(
        "slack_name"=> $slack_name,
        "current_day"=> $current_day,
        "utc_time"=> $utc_time,
        "track"=> $track,
        "github_file_url"=> $github_file_url,
        "github_repo_url"=> $github_repo_url,
        "status_code"=> $status_code
    );
    // returning the response in json format
    return json_encode($response);
}

// Setting the resultant output to be json type of content
header('Content-Type: application/json');

if($return === true){
    // Calling the response function for ideal case
    $endpoints = response($slack_name, $track, $github_file_url, $github_repo_url);
    echo str_replace("\\", "", $endpoints);
}else{
    // Calling the false case
    $response = array(
        "status_code"=> $status_code,
        "status_massage"=> $status_massage,
    );
    echo json_encode($response);
}

