<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$name = $_POST['userNickName'];
$userIndex = $_POST['userIndex'];


$sql = "UPDATE room_tb SET name_host = '$name', host_index = $userIndex WHERE id = $roomId";
$result = mysqli_query($con, $sql);
if ($result) {
    echo "true";
} else {
    echo "false";
}
