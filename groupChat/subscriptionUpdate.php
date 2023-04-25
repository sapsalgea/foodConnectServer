<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");


$subNum = $_POST['subNumber'];
$status = $_POST['status'];
$fcmsql = "SELECT r.id,r.room_title, u.user_token, r.name_host FROM subscription_join sj join user_tb u on sj.user_id = u.id join room_tb r on r.id = sj.room_id WHERE sj.id = $subNum";
$sql = "UPDATE subscription_join SET status = $status WHERE id = $subNum";
$fcmResult = mysqli_query($con,$fcmsql);
$row = $fcmResult->fetch_assoc();
$result = mysqli_query($con, $sql);

if ($result) {
    if ($status == 3) {
        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        $header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
        $data = json_encode(array(
            "to" => $row['user_token'],
            "priority" => "high",
            "data" => array(
                "title"   => "참여신청이 거절 되었습니다.",
                "body" => $row['room_title']."방에 보낸 참여신청이 거절 되었습니다.",
                "roomId" => $row['id'],
                "hostName"=> $row['name_host'],
                "cancel" =>"true"
            )
        ));

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        curl_exec($ch);
    }
    echo "true";
} else {
    echo "false";
}
