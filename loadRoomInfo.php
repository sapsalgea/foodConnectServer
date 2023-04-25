<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$userIndex = $_POST['userIndex'];

$sql = "SELECT r.id,
r.room_title,
r.room_introduce,
r.now_member_count,
r.member_count,
r.restaurant_address,
r.restaurant_roadaddress,
r.restaurant_placename,
r.gender_selection,
r.minimum_age,
r.maximum_age,
r.appointment_day,
r.appointment_time,
r.search_keyword,
r.map_x,
r.map_y,
r.host_index,
r.restaurant_name,
 u.nick_name,
 u.profile_image, COUNT(jr.join_room_tb_id) AS join_count 
 FROM room_tb r 
 JOIN user_tb u 
 ON u.id = r.host_index 
 JOIN join_room_tb jr 
 ON jr.room_id = r.id 
 WHERE r.id = $roomId 
 AND jr.user_index = $userIndex";

$result = mysqli_query($con, $sql);

if ($result) {
    $row = $result->fetch_assoc();
    $nowtime = date('Y-m-d H:i:s');
    $settime = $row['appointment_day'] . " " . $row['appointment_time'];
    $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;
    echo json_encode($array = array(
        "roomId" => $row['id'],
        "title" => $row['room_title'],
        "info" => $row['room_introduce'],
        "nowNumOfPeople" => $row['now_member_count'],
        "numOfPeople" => $row['member_count'],
        "address" => $row['restaurant_address'],
        "roadAddress" => $row['restaurant_roadaddress'],
        "shopName" => $row['restaurant_name'],
        "gender" => $row['gender_selection'],
        // "placeName" => $row['restaurant_placename'],
        "minimumAge" => $row['minimum_age'],
        "maximumAge" => $row['maximum_age'],
        // "reporting_date" => $row['reporting_date'],
        "date" => $row['appointment_day'],
        // "time" => $row['appointment_time'],
        "hostName" => $row['nick_name'],
        "hostIndex" => $row['host_index'],
        "roomStatus" => $roomStatus,
        "keyWords" => $row['search_keyword'],
        "map_x" => $row['map_x'],
        "map_y" => $row['map_y'],
        // "joinMember" => json_decode($row['join_users']),
        // "finish" => $row['finish'],
        "joinCount" =>$row['join_count'],
        "imageUrl" => $row['profile_image']
    ), JSON_UNESCAPED_UNICODE);
}else{
    echo "실패";
}
