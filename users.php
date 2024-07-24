<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT");

    $conn = mysqli_connect("localhost", "u606518727_rcrud", "P4tr1ckSt4r", "u606518727_patrick_rcrud");

    if(!$conn) {
        die("ERROR: Could Not Connect" . mysqli_connect_error());
    }

    $method = $_SERVER['REQUEST_METHOD'];

    switch($method) {

        case "GET":
            $path = explode('/', $_SERVER['REQUEST_URI']);
            if(isset($path[5]) && is_numeric($path[5])) {
                $sql = "SELECT * FROM users WHERE userid = '$path[5]'";
            } else {
                $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
            }
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo json_encode($users);
                return;
            } else {
                echo json_encode([]);
                return;
            }
            break;

        case "POST":
            $new_user = json_decode(file_get_contents("php://input"));
            $sql = "INSERT INTO users(username, useremail, status) VALUES('$new_user->username', '$new_user->useremail', '$new_user->status')";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo json_encode(["success"=>"User added successfully"]);
                return;
            } else {
                echo json_encode(["success"=>"Please check the user data"]);
                return;
            }
            break;

        case "PUT":
            $updated_user = json_decode(file_get_contents("php://input"));
            if($updated_user->method == "update") {
                $sql = "UPDATE users SET username = '$updated_user->username', useremail = '$updated_user->useremail', status = '$updated_user->status'  WHERE userid = '$updated_user->userid'";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    echo json_encode(["success"=>"User record updated successfully"]);
                    return;
                } else {
                    echo json_encode(["success"=>"Please check the user data"]);
                    return;
                }
            }
            if($updated_user->method == "delete") {
                $sql = "UPDATE users SET deleted_at = CURRENT_TIMESTAMP  WHERE userid = '$updated_user->userid'";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    echo json_encode(["success"=>"User record deleted successfully"]);
                    return;
                } else {
                    echo json_encode(["success"=>"Please check the user data"]);
                    return;
                }
            }
            break;
    }
?>