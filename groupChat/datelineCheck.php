<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomid = $_POST['roomId'];
$time = date('Y-m-d');
$time = $time . " 00:00:00";

$sql = "SELECT * FROM group_message_tb WHERE to_room_id ='$roomid' and message_type = 'TIMELINE' and sendtime = '$time'";

$result = mysqli_query($con, $sql);
$num = mysqli_num_rows($result);

$response = array();

if ($num == 0) {
    $response['date'] = true;
    $sql2 = "SELECT join_users from room_tb WHERE id = '$roomid'";
    $result2 = mysqli_query($con, $sql2);
    $row = $result2->fetch_assoc();
    $response['members'] = $row['join_users'];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    $response['date'] = false;
    $sql2 = "SELECT join_users from room_tb WHERE id = '$roomid'";
    $result2 = mysqli_query($con, $sql2);
    $row = $result2->fetch_assoc();
    $response['members'] = $row['join_users'];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
