<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");
$userIndexId = $_POST['userIndexId'];
$title = $_POST['title'];
$info = $_POST['info'];
$numOfPeople = $_POST['numOfPeople'] + 1;
$date = $_POST['date'];
$time = $_POST['time'];
$address = $_POST['address'];
$roadAddress = $_POST['roadAddress'];
$placeName = $_POST['placeName'];
$shopName = $_POST['shopName'];
$keyWords = $_POST['keyWords'];
$gender = $_POST['gender'];
$minimumAge = $_POST['minimumAge'];
$maximumAge = $_POST['maximumAge'];
$hostName = $_POST['hostName'];
$map_x = $_POST['map_x'];
$map_y = $_POST['map_y'];
$joinNick = json_encode(array("$userIndexId"),JSON_UNESCAPED_UNICODE);

$now = date('Y-m-d H:i:s');

$settime = $date . " " . $time;
$roomStatus = (strtotime($settime) - strtotime($now)) / 3600;

$sql = "INSERT INTO room_tb(
room_title,
room_introduce,
member_count,
restaurant_address,
restaurant_roadaddress,
restaurant_placename,
restaurant_name,
gender_selection,
minimum_age,
maximum_age,
reporting_date,
appointment_day,
appointment_time,
name_host,
host_index,
room_status,
search_keyword,
map_x,
map_y,
join_users)
VALUES(
'$title',
'$info',
'$numOfPeople',
'$address',
'$roadAddress',
'$placeName',
'$shopName',
'$gender',
'$minimumAge',
'$maximumAge',
'$now',
'$date',
'$time',
'$hostName',
'$userIndexId',
$roomStatus,
'$keyWords',
'$map_x',
'$map_y',
'$joinNick')";

$result = mysqli_query($con, $sql);

$response = array();

if ($result) {
    $createRoomId = mysqli_insert_id($con);
    $hostJoinRoomSQL = "INSERT INTO join_room_tb (room_id,user_index, user_nickname, join_datetime)VALUES('$createRoomId','$userIndexId', '$hostName', '$now')";
    $hostJoinRoomRecordSQL = "INSERT INTO join_room_record_tb (room_id,user_index, user_nickname, join_datetime)VALUES('$createRoomId', '$userIndexId','$hostName', '$now')";
    $hostJoinServer = "INSERT INTO group_message_tb (to_room_id,from_user_id,user_index,message_type,content,thumbnailImage,sendtime,join_members)VALUES('$createRoomId','JOINMEMBER','$userIndexId','JOINMEMBER',   '$hostName 님이 입장하셧습니다.'   ,'SERVER','".date('Y-m-d H:i:s')."','".json_encode(array($userIndexId))."')";
    $JoinResult = mysqli_query($con, $hostJoinRoomSQL);
    $JoinRecordResult = mysqli_query($con, $hostJoinRoomRecordSQL);
    $JoinServerResult = mysqli_query($con,$hostJoinServer);
    $nowtime = date('Y-m-d H:i:s');
    $settime = $date . " " . $time;
    $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;
    $array = array(
        "roomId" => $createRoomId,
        "title" => $title,
        "info" => $info,
        "nowNumOfPeople" => 1,
        "numOfPeople" => $numOfPeople,
        "address" => $address,
        "roadAddress" => $roadAddress,
        "shopName" => $shopName,
        "gender" => $gender,
        "placeName" => $placeName,
        "minimumAge" => $minimumAge,
        "maximumAge" => $maximumAge,
        "reporting_date" => $now,
        "date" => $date,
        "time" => $time,
        "hostName" => $hostName,
        "hostIndex"=> $userIndexId,
        "roomStatus" => $roomStatus,
        "keyWords" => $keyWords,
        "map_x" => $map_x,
        "map_y" => $map_y

    );
    if ($JoinRecordResult && $JoinResult&&$JoinServerResult) {
        $response['success'] = true;
        $response['room'] = $array;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } else {
        $response['success'] = false;
        $response['roomId'] = null;
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
} else {
    $response['success'] = false;
    $response['roomId'] = "밖";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
