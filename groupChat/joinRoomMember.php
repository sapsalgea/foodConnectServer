<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$sql = "SELECT u.id, u.thumbnail_image, u.nick_name 
FROM user_tb u 
LEFT JOIN join_room_tb jr 
ON u.nick_name = jr.user_nickname 
WHERE jr.room_id = '$roomId' 
AND u.account_delete = 0 
ORDER BY jr.join_datetime ASC";
$memberSQL = "SELECT join_users FROM room_tb WHERE id = $roomId";
$memberResult = mysqli_query($con,$memberSQL);
$result = mysqli_query($con, $sql);
$response = array();
$list = array();
if ($result) {

    while ($row = $result->fetch_assoc()) {
        $array = array("userIndexId" => $row['id'], "userThumbnail" => $row['thumbnail_image'], "userNickname" => $row['nick_name']);
        array_push($list, $array);
    }
    $memberRow = $memberResult->fetch_assoc();
    $response['members'] = $memberRow['join_users'];
    $response['list'] = $list;
    
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
}
