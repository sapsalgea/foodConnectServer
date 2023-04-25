<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$con2 = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "test");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];
$sql = "SELECT r.id,
r.restaurant_name,
r.restaurant_placename,
r.map_x,
r.map_y,
u.id AS uid, u.thumbnail_image, u.nick_name, 
l.map_x AS user_map_x, 
l.map_y AS user_map_y 
FROM user_tb u 
LEFT JOIN join_room_tb jr 
ON u.id = jr.user_index 
JOIN room_tb r 
ON r.id = jr.room_id 
JOIN (
    SELECT user_index,map_x ,map_y
    FROM(SELECT *
        FROM user_location_tracking_tb
        WHERE (user_index, date_time) 
        IN (
            SELECT user_index, max(date_time) AS date_time
            FROM user_location_tracking_tb 
            GROUP BY user_index
	)ORDER BY date_time DESC 
)AS t GROUP BY t.user_index,t.map_x,map_y) l 
ON l.user_index = u.id 
WHERE jr.room_id ='$roomId'";

$result = mysqli_query($con, $sql);
$response = array();
$members = array();
if ($result) {
    $response['success'] = true;
    $nowtime = date('Y-m-d H:i:s');
    $settime = $row['appointment_day'] . " " . $row['appointment_time'];
    $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;
    while ($row = $result->fetch_assoc()) {

        $array = array("userIndexId" => $row['uid'], "userThumbnail" => $row['thumbnail_image'], "userNickname" => $row['nick_name'], "x" => $row['user_map_x'], "y" => $row['user_map_y']);
        array_push($members, $array);
        $response['roomInfo'] = array(
            "roomId" => $row['id'],
            // "title" => $row['room_title'],
            // "info" => $row['room_introduce'],
            // "nowNumOfPeople" => $row['now_member_count'],
            // "numOfPeople" => $row['member_count'],
            // "address" => $row['restaurant_address'],
            // "roadAddress" => $row['restaurant_roadaddress'],
            "shopName" => $row['restaurant_name'],
            // "gender" => $row['gender_selection'],
            "placeName" => $row['restaurant_placename'],
            // "minimumAge" => $row['minimum_age'],
            // "maximumAge" => $row['maximum_age'],
            // "reporting_date" => $row['reporting_date'],
            // "date" => $row['appointment_day'],
            // "time" => $row['appointment_time'],
            // "hostName" => $row['name_host'],
            // "roomStatus" => $roomStatus,
            // "keyWords" => $row['search_keyword'],
            "map_x" => $row['map_x'],
            "map_y" => $row['map_y'],
            // "joinMember" => json_decode($row['join_users'])
        );
    }

    $response['members'] = $members;

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}else{
    $response['success'] = false;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
