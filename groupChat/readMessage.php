<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$userIndex = $_POST['userIndex'];
$rooId = $_POST['roomId'];

$sql = "UPDATE group_message_tb
SET join_members = IF(replace(json_search(join_members , 'one', '$userIndex'), '\"','') IS NOT NULL , JSON_REMOVE(
join_members , replace(json_search(join_members, 'one', '$userIndex'), '\"', '')
),join_members) WHERE to_room_id = '$rooId'
";
$result = mysqli_query($con, $sql);

if ($result) {
    echo "true";
} else {
    echo "false";
}
