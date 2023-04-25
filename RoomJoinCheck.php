<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$nickName = $_POST['nickName'];
$hostName = $_POST['hostName'];
$response = array();
$response['hostName'] = $hostName;

$CheckSQL = "SELECT * FROM join_room_tb WHERE room_id = '$roomId' and user_nickname = '$nickName' LIMIT 1";
$roomSQL = "SELECT * FROM room_tb Where id = $roomId";
$CheckResult = mysqli_query($con, $CheckSQL);
$roomResult = mysqli_query($con, $roomSQL);
$CheckNum = mysqli_num_rows($CheckResult);
$RoomNum = mysqli_num_rows($roomResult);

if ($RoomNum != 0) {

    if ($CheckNum == 0) {
        $response['success'] = true;

        $getImageSQL = "SELECT profile_image FROM user_tb WHERE nick_name ='$hostName' LIMIT 1";

        $getImageResult = mysqli_query($con, $getImageSQL);

        $row = $getImageResult->fetch_assoc();

        if ($getImageResult) {
            $response['imageUrl'] = $row['profile_image'];
            $response['isRoom'] = true;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $response['success'] = false;
        $getImageSQL = "SELECT profile_image FROM user_tb WHERE nick_name ='$hostName' LIMIT 1";

        $getImageResult = mysqli_query($con, $getImageSQL);

        $row = $getImageResult->fetch_assoc();

        if ($getImageResult) {
            $response['imageUrl'] = $row['profile_image'];
            $response['isRoom'] = true;
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }
} else {
    $response['success'] = false;
    $response['isRoom'] = false;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
