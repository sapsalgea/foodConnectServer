<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];

$loadingQuery = "SELECT * FROM room_tb where id = $roomId ";

$result = mysqli_query($con, $loadingQuery);

if ($result) {
    $roomList = array();
    
    //리스폰 배열에 모든방정보 등록
    while ($row = $result->fetch_assoc()) {

        /** 오늘날짜와 약속날짜 차이계산해서 방 상태산출 */
        $nowtime = date('Y-m-d H:i:s');
        $settime = $row['appointment_day'] . " " . $row['appointment_time'];
        $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;


        //방하나의 객체가될 배열
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
            "hostName" => $row['name_host'],
            "roomStatus" => $roomStatus,
            "keyWords" => $row['search_keyword'],
            "map_x" => $row['map_x'],
            "map_y" => $row['map_y'],
            "joinMember" => json_decode($row['join_users']),
            "finish"=> $row['finish']
        );

        //roomList 배열에 배열저장
        array_push($roomList, $array);
    }

    //리스폰 방리스트 배열 JSON인코딩        
    $response['success'] = true;
    $response['work'] = "방쿼리성공";
    $response['roomList'] = $roomList;

    //리스폰 데이터 JSON인코딩으로 보내기
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
