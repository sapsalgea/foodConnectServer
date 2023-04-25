<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");
$dateYmdhis = 'Y-m-d H:i:s';
$userIndex = $_POST['userIndex'];
$today = date('Y-m-d');
$toTime =date('H:i:s');
$sql = "SELECT * FROM room_tb WHERE appointment_day = '$today' AND appointment_time > '$toTime' AND JSON_EXTRACT(join_users, replace(json_search(join_users, 'one', '$userIndex'), '\"', ''))";

$result = mysqli_query($con, $sql);
$response['list'] = array();
if (mysqli_num_rows($result) > 0) {

    while ($row = $result->fetch_assoc()) {

        $roomtime = $row['appointment_day'] . ' ' . $row['appointment_time'];
        $roomLocationTime = strtotime(date($dateYmdhis, strtotime($roomtime . "-1 hours")));
        date($dateYmdhis, $roomLocationTime);
        $nowtime =  strtotime(date($dateYmdhis));
        $remainingTime = $roomLocationTime - $nowtime;
        $array = array("roomId" => $row['id'], "time" => $remainingTime);
        array_push($response['list'], $array);
    }
    echo json_encode($response);
} else {
    echo "sss";
}
