<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$userIndex = $_POST['userIndex'];

$sql = "SELECT jr.room_id,r.join_users,r.name_host FROM join_room_tb jr join room_tb r on jr.room_id = r.id WHERE jr.user_index = $userIndex";

$result = mysqli_query($con, $sql);
$response = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $array =  array(
            "roomId" => $row['room_id'],
            "members" => $row['join_users'],
            "hostName" => $row['name_host']
        );
        array_push($response, $array);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
