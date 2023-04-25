<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$userIndexid = $_POST['userIndexId'];
$userNickName = $_POST['userNickName'];

$sql = "SELECT r.id,
r.room_title,
r.now_member_count,
r.restaurant_placename,
r.name_host,
lastGroupMsg.message_type, lastGroupMsg.content,lastGroupMsg.sendtime,readm.nonread 
FROM room_tb AS r 
JOIN join_room_tb AS jt 
ON r.id = jt.room_id 
JOIN (
    SELECT to_room_id ,content,sendtime,message_type
        FROM(
            SELECT to_room_id, content, sendtime, message_type
            FROM group_message_tb
            WHERE (to_room_id, sendtime) 
                in (
                    SELECT to_room_id, max(sendtime) AS sendtime
                    FROM group_message_tb 
                    GROUP BY to_room_id 
                ) ORDER BY sendtime DESC 
            ) AS lastMsg 
        GROUP BY lastMsg.to_room_id,lastMsg.content,lastMsg.sendtime,lastMsg.message_type) AS lastGroupMsg 
    ON lastGroupMsg.to_room_id = r.id 
JOIN (
    SELECT to_room_id , 
    COUNT(JSON_EXTRACT(join_members, replace(json_search(join_members, 'one', '$userIndexid'), '\"', ''))) AS nonread 
    FROM group_message_tb GROUP BY to_room_id) AS readm 
    ON readm.to_room_id = r.id 
WHERE jt.user_index ='$userIndexid' 
ORDER BY lastGroupMsg.sendtime DESC";

$result = mysqli_query($con, $sql);
$roomList = array();
if ($result) {
    while ($row = $result->fetch_array()) {

        $nowtime = date('Y-m-d H:i:s');
        $settime = $row['appointment_day'] . " " . $row['appointment_time'];
        $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;
        $content = $row['content'];
        if ($row['message_type'] == "IMAGE") {
            $content = "이미지";
        }
        $array = array(
            "roomId" => $row['id'],
            "title" => $row['room_title'],
            // "info" => $row['room_introduce'],
            "nowNumOfPeople" => $row['now_member_count'],
            // "numOfPeople" => $row['member_count'],
            // "address" => $row['restaurant_address'],
            // "roadAddress" => $row['restaurant_roadaddress'],
            // "shopName" => $row['restaurant_name'],
            // "gender" => $row['gender_selection'],
            "placeName" => $row['restaurant_placename'],
            // "minimumAge" => $row['minimum_age'],
            // "maximumAge" => $row['maximum_age'],
            // "reporting_date" => $row['reporting_date'],
            // "date" => $row['appointment_day'],
            // "time" => $row['appointment_time'],
            "hostName" => $row['name_host'],
            // "roomStatus" => $roomStatus,
            // "keyWords" => $row['search_keyword'],
            // "map_x" => $row['map_x'],
            // "map_y" => $row['map_y'],
            // "joinMember" => json_decode($row['join_users']),
            "content" => $content,
            // "fromId" => $row['from_id'],
            "sendTime" => $row['sendtime'],
            "nonRead" => $row['nonread'],
            // "finish" => $row['finish']

        );

        //roomList 배열에 배열저장
        array_push($roomList, $array);
    }
    $response['success'] = true;
    $response['work'] = "방쿼리성공";
    $response['roomList'] = $roomList;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
