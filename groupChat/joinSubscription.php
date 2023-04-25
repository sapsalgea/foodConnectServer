<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$userIndexId = $_POST['userIndexId'];
$sqlNumOfPeople = "SELECT r.id, r.name_host, r.now_member_count,r.member_count ,r.room_title,u.user_token FROM room_tb r join user_tb u ON r.name_host = u.nick_name WhERE r.id = $roomId";
$numOfPeopleResult = mysqli_query($con, $sqlNumOfPeople);

$sqlJoinCheck = "SELECT * FROM subscription_join WHERE user_id = '$userIndexId' and room_id = '$roomId' and status in(0,1) ";
$resultJoinCheck = mysqli_query($con, $sqlJoinCheck);

$row = $numOfPeopleResult->fetch_assoc();
$hostToken = $row['user_token'];

$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
$data = json_encode(array(
    "to" => $hostToken,
    "priority" => "high",
    "data" => array(
        "title"   => "참여 신청이 왔습니다.",
        "body" => $row['room_title']."방에 참여신청이 왔습니다.",
        "roomId" => $row['id'],
        "hostName"=>$row['name_host'],
        "subscription"=>"true"
    )
));



if ($row['now_member_count'] < $row['member_count']) {
    if (mysqli_num_rows($resultJoinCheck) == 0) {

        $sql = "INSERT INTO subscription_join (user_id,room_id,status)VALUES('$userIndexId','$roomId',0)";

        $result = mysqli_query($con, $sql);

        if ($result) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       
            curl_exec($ch);
            
            $response['status'] = "true";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            $response['status'] = "false";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $response['status'] = "false";
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} else {
    $response['status'] = "full";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
