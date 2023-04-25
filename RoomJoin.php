<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");



$roomId = $_POST['roomId'];

$nickName = $_POST['nickName'];

$userIndexId = $_POST['userIndexId'];

$now = date('Y-m-d H:i:s');

$fcmToken = "SELECT u.user_token,r.room_title,r.id,r.name_host FROM user_tb u join room_tb r on r.id = $roomId WHERE u.id = $userIndexId";

$fcmResult = mysqli_query($con,$fcmToken);

$row = $fcmResult->fetch_assoc();

$sql = "INSERT INTO join_room_tb (room_id,user_index, user_nickname, join_datetime)VALUES('$roomId','$userIndexId', '$nickName', '$now')";

$sql2 = "INSERT INTO join_room_record_tb (room_id, user_index,user_nickname, join_datetime)VALUES('$roomId', '$userIndexId','$nickName', '$now')";

$sql3 = "UPDATE room_tb SET now_member_count = now_member_count+1 WHERE id = $roomId";

$sqlRoomMember = "SELECT join_users FROM room_tb WHERE id = $roomId LIMIT 1";

$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
$data = json_encode(array(
    "to" => $row['user_token'],
    "priority" => "high",
    "data" => array(
        "title"   => "방에 참가 되었습니다.",
        "body" => $row['room_title']."방에 참가가 되었습니다.",
        "roomId" => $row['id'],
        "hostName"=>$row['name_host'],
        "join" =>"1"
        
        
    )
));

$roomMemberResult = mysqli_query($con, $sqlRoomMember);

$row = $roomMemberResult->fetch_assoc();

$resultArray = json_decode($row['join_users']);

array_push($resultArray, $userIndexId);

$arrayToJson = json_encode($resultArray, JSON_UNESCAPED_UNICODE);


$sqlRoomMember2 = "UPDATE room_tb SET join_users = '$arrayToJson' WHERE id = $roomId LIMIT 1";

$resultroomMemberModify = mysqli_query($con, $sqlRoomMember2);


$response = array();

$result1 = mysqli_query($con, $sql);

$result2 = mysqli_query($con, $sql2);

$result3 = mysqli_query($con, $sql3);

$response = array();
if ($result1 && $result2 &&$result3&& $resultroomMemberModify) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_exec($ch);
    $response['success'] = true;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    $response['success'] = false;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
