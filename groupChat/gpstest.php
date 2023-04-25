<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "test");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");
$userIndax = $_POST['userIndex'];
$x = $_POST["x"];
$y = $_POST["y"];
$date = date("Y-m-d H:i:s.u");
$sql = "INSERT INTO user_location_tracking_tb(user_index,map_x,map_y)VALUES($userIndax,'$x','$y')";

$result = mysqli_query($con, $sql);


if ($result) {
    echo "true";
} else {
    echo "false";
}
