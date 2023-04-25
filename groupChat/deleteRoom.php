<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$userIndex = $_POST['userIndexId'];
$userNickName = $_POST['userNickName'];

$sql = "DELETE FROM room_tb where id = $roomId";

$sql2 = "DELETE FROM join_room_tb WHERE room_id = $roomId and user_nickname = '$userNickName'";

// $sql3 = "UPDATE room_tb SET now_member_count = now_member_count-1 WHERE id = $roomId";

// $sql4 = "UPDATE room_tb
// SET join_users = IF(replace(json_search(join_users , 'one', '$userIndex'), '\"','') IS NOT NULL , JSON_REMOVE(
//     join_users , replace(json_search(join_users, 'one', '$userIndex'), '\"', '')
// ),join_users) WHERE to_id = '$roomId'";

$result = mysqli_query($con, $sql);

$result2 = mysqli_query($con, $sql2);

// $result3 = mysqli_query($con,$sql3);

// $result4 = mysqli_query($con,$sql4);

if ($result && $result2) {


    echo "true";
} else {
    echo "false2";
}
