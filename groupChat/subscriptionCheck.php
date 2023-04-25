<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];

$sql = "SELECT sub.id AS sub_id, sub.room_id, sub.status ,u.id 
AS u_id,u.profile_image,u.thumbnail_image,u.nick_name 
FROM subscription_join sub 
JOIN user_tb u 
ON u.id = sub.user_id 
WHERE sub.room_id = '$roomId' 
ORDER BY sub.id ASC";

$result = mysqli_query($con, $sql);
$response = array();
$userList = array();
if (mysqli_num_rows($result)>0) {
$response['success'] = true;
    while ($row = $result->fetch_assoc()) {

        $array = array(
            "subscriptionId"=>$row['sub_id'],
            "userIndexId" => $row['u_id'],
            "nickName" => $row['nick_name'],
            "profileImage" => $row['profile_image'],
            "thumbnailImage" => $row['thumbnail_image'],
            "status" => $row['status'],
            "roomId" => $row['room_id']
        );
        
        array_push($userList,$array);
    }
$response['userList'] = $userList;

echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else{
    $response['success'] = false;
    $response['userList'] = null;
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
}
