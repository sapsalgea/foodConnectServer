<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$now = strtotime(date("Y-m-d H:i"));
$nowDay = date("Y-m-d");
$hourMinars = strtotime(date("Y-m-d H:i") . "-3 hours");
$splitTimeFormet1 = date("Y-m-d", $hourMinars);

$splitTimeFormet2 = date("H:i", $hourMinars).":00";

$sql = "SELECT r.*,u.user_token FROM room_tb r RIGHT JOIN (SELECT * From join_room_tb) jr on r.id = jr.room_id JOIN user_tb u ON u.nick_name = r.name_host WHERE appointment_day = '$splitTimeFormet1' and appointment_time = '$splitTimeFormet2' AND r.room_status = 0 and r.now_member_count>1 and r.finish = 0 AND jr.meeting_result = 0";
$result = mysqli_query($con, $sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        $header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
        $data = json_encode(array(
            "to" => $row['user_token'],
            "priority" => "high",
            "data" => array(
                "title"   => "즐거운 모임되셧나요?",
                "body" => "모임이 끝났다면 채팅방에서 모임 완료 버튼을 눌러주세요",
                "roomId" => $row['id'],
                "hostName"=>$row['name_host'],
                "finish"=>"true"
            )
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   
        curl_exec($ch);
    }
}
