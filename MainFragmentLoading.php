<?php
//DB연결
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

//유저의 아이디값 받기
$userId = $_POST['userId'];

//유저의 아이디가 존재하는지 체크하는쿼리
$userConfirmQuery = "SELECT user_id from user_tb WHERE user_id ='$userId' LIMIT 1";

//리스폰 배열 생성
$response = array();

$response['id'] = $userId;

//유저 아이디 존재여부 쿼리요청
$result = $con->query($userConfirmQuery);
$week = strtotime("-1 days");
$today = date('Y-m-d', $week);


//유저 아이디가 존재 할시 if 수행
if (mysqli_num_rows($result) == 1) {

    $response['work'] = "유저존재확인";
    // 모든 방정보 요청 쿼리
    $loadingQuery = "SELECT 
    r.id,
    r.room_title,
    r.now_member_count,
    r.member_count,
    r.restaurant_address,
    r.restaurant_roadaddress,
    r.restaurant_name,
    r.gender_selection,
    r.restaurant_placename,
    r.minimum_age,
    r.maximum_age,
    r.join_users,
    r.finish,
    r.appointment_day,
    r.appointment_time,
    r.gender_selection,
    u.nick_name
    FROM room_tb r 
    join user_tb u 
    on u.id = r.host_index 
    where r.appointment_day >'$today' 
    and r.appointment_time > '00:00:00' 
    and finish = 0 
    ORDER BY r.reporting_date DESC";
    

    //모든 방정보 요청하기
    $loadingResult = mysqli_query($con, $loadingQuery);

    if ($loadingResult) {

        $roomList = array();
        $statusUpdate = array();
        //리스폰 배열에 모든방정보 등록
        while ($row = $loadingResult->fetch_assoc()) {

            /** 오늘날짜와 약속날짜 차이계산해서 방 상태산출 */
            $nowtime = date('Y-m-d H:i:s');
            $settime = $row['appointment_day'] . " " . $row['appointment_time'];
            $roomStatus = (strtotime($settime) - strtotime($nowtime)) / 3600;

            /** 약속날짜가 오늘 날짜를 지나가게되면 해당 게시글 id를 배열에 저장 */
            if ($roomStatus < 0) {
                array_push($statusUpdate, (int)$row['id']);
            }else{

            //방하나의 객체가될 배열
            $array = array(
                "roomId" => $row['id'],
                "title" => $row['room_title'],                
                "nowNumOfPeople" => $row['now_member_count'],
                "numOfPeople" => $row['member_count'],
                "address" => $row['restaurant_address'],
                "roadAddress" => $row['restaurant_roadaddress'],
                "shopName" => $row['restaurant_name'],
                "gender" => $row['gender_selection'],
                "placeName" => $row['restaurant_placename'],
                "minimumAge" => $row['minimum_age'],
                "maximumAge" => $row['maximum_age'],
                "date" => $row['appointment_day'],
                "hostName" => $row['nick_name'],
                "roomStatus" => $roomStatus,
                "joinMember" => json_decode($row['join_users']),
                "finish"=> $row['finish']
            );

            //roomList 배열에 배열저장
            array_push($roomList, $array);}
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
} else {
    //유저 아이디정보 없을시 실패처리

    $response['success'] = false;
    $response['roomList'] = null;

    echo json_encode($response, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
}
//DB연결 끊기
