<?php



$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];

$sql = "SELECT now_member_count, member_count FROM room_tb WHERE id = $roomId";

$result = $con->query($sql);



if ($result) {
   
    $row = $result->fetch_assoc();

    if ($row['now_member_count'] < $row['member_count']) {
        echo "true";
    } else {
        echo "false";
    }
}
