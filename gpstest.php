<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$userIndex = $_POST['userIndex'];
$x = $_POST["x"];
$y = $_POST["y"];
$t = microtime(true);
$micro = sprintf("%06d", ($t - floor($t)) * 1000000);
$d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
$date = $d->format("Y-m-d H:i:s.u");
$today = date('Y-m-d');
$toTime = date('H:i:s');

$sql = "INSERT INTO user_location_tracking_tb (user_index, map_x, map_y, date_time)VALUES('$userIndex','$x','$y','$date')";

$result = mysqli_query($con, $sql);

$sqlCheck = "SELECT * FROM room_tb WHERE appointment_day = '$today' /*AND appointment_time > '$toTime'*/ AND JSON_EXTRACT(join_users, replace(json_search(join_users, 'one', '$userIndex'), '\"', ''))";
$resultCheck = mysqli_query($con, $sqlCheck);
$response['list'] = array();
if ($resultCheck) {
    if (mysqli_num_rows($resultCheck) > 0) {
        $CheckTime = "false";
        while ($row = $resultCheck->fetch_assoc()) {

            $dateYmdhis = 'Y-m-d H:i:s';
            $roomtime = $row['appointment_day'] . ' ' . $row['appointment_time'];
            $roomLocationTime = strtotime(date($dateYmdhis, strtotime($roomtime . "-1 hours")));
            date($dateYmdhis, $roomLocationTime);
            $nowtime =  strtotime(date($dateYmdhis));
            $row['room_title'] . $remainingTime = $roomLocationTime - $nowtime;
            if ($remainingTime >= -3600 && $remainingTime <= 0) {
                $CheckTime = "true";
                // echo $remainingTime;
                break;
            }
        }
        echo $CheckTime;
    } else {
        echo "false1";
    }
} else {
    echo "실패";
}
