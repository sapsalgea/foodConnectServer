<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$type = $_POST['type'];
$content = $_POST['content'];

switch ($type) {

    case "전체":
        $sql = "SELECT * FROM room_tb WHERE (
            room_title like '%$content%' OR
            restaurant_address like '%$content%' OR
            restaurant_roadaddress like '%$content%' OR
            restaurant_placename like '%$content%' OR
            restaurant_name like '%$content%' OR
            search_keyword like '%$content%'           
            ) ORDER BY id desc";
        $resulte = mysqli_query($con, $sql);
        break;
    case "방제목":
        $sql = "SELECT * FROM room_tb WHERE(
            room_title like '%$content%'           
            ) ORDER BY id desc";
        $resulte = mysqli_query($con, $sql);
        break;
    case "매장명":
        $sql = "SELECT * FROM room_tb WHERE(         
            restaurant_placename like '%$content%' OR
            restaurant_name like '%$content%'          
            ) ORDER BY id desc";
        $resulte = mysqli_query($con, $sql);
        break;
    case "지역":
        $sql = "SELECT * FROM room_tb WHERE(            
            restaurant_address like '%$content%' OR
            restaurant_roadaddress like '%$content%'           
            ) ORDER BY id desc";
        $resulte = mysqli_query($con, $sql);
        break;
    case "키워드":
        $sql = "SELECT * FROM room_tb WHERE(
            search_keyword like '%$content%'           
            ) ORDER BY id desc";
        $resulte = mysqli_query($con, $sql);
        break;
}

if ($resulte) {

    $roomList = array();
    $statusUpdate = array();
    //리스폰 배열에 모든방정보 등록
    while ($row = $resulte->fetch_assoc()) {

        /** 오늘날짜와 약속날짜 차이계산해서 방 상태산출 */
        $nowtime = date('Y-m-d H:i:s');
        $settime = $row['appointment_day'] . " " . $row['appointment_time'];
        $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;

        /** 약속날짜가 오늘 날짜를 지나가게되면 해당 게시글 id를 배열에 저장 */
        if ($roomStatus < 0) {
            array_push($statusUpdate, (int)$row['id']);
        }

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
            "joinMember" => json_decode($row['join_users'])
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

    //업데이트문으로 약속날짜가 지나버린 게시물 상태 업데이트 쿼리문
    $statusUpdateSQL = "UPDATE room_tb SET room_status = 0 WHERE id IN (" . implode(',', $statusUpdate) . ")";

    //쿼리요청
    mysqli_query($con, $statusUpdateSQL);
} else {
    $response['success'] = false;
    $response['work'] = "방쿼리실패";
    $response['roomList'] = null;
    echo json_encode($response, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
}
