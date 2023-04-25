<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];

$sql = "SELECT room_tb.id,
room_tb.room_title,
room_tb.room_introduce,
room_tb.now_member_count,
room_tb.member_count,
room_tb.restaurant_address,
room_tb.restaurant_roadaddress,
room_tb.restaurant_placename,
room_tb.restaurant_name,
room_tb.gender_selection,
room_tb.minimum_age,
room_tb.maximum_age,
room_tb.appointment_day,
room_tb.appointment_time,
room_tb.search_keyword,
room_tb.map_x,
room_tb.map_y,
room_tb.finish,
 user_tb.nick_name 
 FROM room_tb join user_tb on room_tb.host_index = user_tb.id  WHERE room_tb.id = '$roomId'";

$result = $con->query($sql);

$roomList = array();
if($result){

    $row = $result ->fetch_assoc();

    $nowtime = date('Y-m-d H:i:s');
    $settime = $row['appointment_day'] . " " . $row['appointment_time'];
    $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;
    
    $array = array(
        "roomId" => $row['id'],
        "title" => $row['room_title'],
        "info" => $row['room_introduce'],
        "nowNumOfPeople" => $row['now_member_count'],
        "numOfPeople" => $row['member_count'],
        "address" => $row['restaurant_address'],
        "roadAddress" => $row['restaurant_roadaddress'],
        "shopName" => $row['restaurant_name'],
        "gender" => $row['gender_selection'],
        "placeName" => $row['restaurant_placename'],
        "minimumAge" => $row['minimum_age'],
        "maximumAge" => $row['maximum_age'],
        "reporting_date" => $row['reporting_date'],
        "date" => $row['appointment_day'],
        "time" => $row['appointment_time'],
        "hostName" => $row['nick_name'],
        "roomStatus" => $roomStatus,
        "keyWords" => $row['search_keyword'],
        "map_x" => $row['map_x'],
        "map_y" => $row['map_y'],
        "joinMember" => json_decode($row['join_users']),
        "finish"=> $row['finish']
    );

    //roomList 배열에 배열저장
    array_push($roomList, $array);


//리스폰 방리스트 배열 JSON인코딩        
$response['success'] = true;
$response['work'] = "방쿼리성공";
$response['roomList'] = $roomList;
echo json_encode($response,JSON_UNESCAPED_UNICODE);
}else{
    $response['success'] = false;
    $response['work'] = "방쿼리실패";
    $response['roomList'] = $roomList;
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
}
