<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];





$UpdateSQL = "UPDATE join_room_tb SET meeting_result = 1 WHERE room_id = '$roomId'";
$UpdateResult = mysqli_query($con, $UpdateSQL);
$UpdateRoomSQL = "UPDATE room_tb SET finish = 1 WHERE id = '$roomId'";
$UpdateRoomResult = mysqli_query($con, $UpdateRoomSQL);


if ($UpdateResult && $UpdateRoomResult) {
    $FCMSQL = "SELECT u.id, u.user_token, jr.room_id,r.name_host FROM user_tb u JOIN join_room_tb jr ON u.id = jr.user_index join room_tb r on jr.room_id = r.id WHERE jr.room_id = '$roomId' and jr.meeting_result = 1";
    $FCMResult = $con->query($FCMSQL);
    if ($FCMResult) {
        while ($FCMrow = $FCMResult->fetch_assoc()) {
            $fch = curl_init("https://fcm.googleapis.com/fcm/send");
            $fheader = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
            $fdata = json_encode(array(
                "to" => $FCMrow['user_token'],
                "priority" => "high",
                "data" => array(
                    "title"   => "모임 완료가 되었습니다!",
                    "body" => "모임이 완료 되었습니다. 리뷰작성과 유저평가를 통해 추가 포인트를 획득해 보세요!",
                    "roomId" => $FCMrow['room_id'],
                    "hostName" => $FCMSQL['name_host'],
                    "finishedGroup" => "true"
                )
            ));
            curl_setopt($fch, CURLOPT_HTTPHEADER, $fheader);
            curl_setopt($fch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($fch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($fch, CURLOPT_POST, 1);
            curl_setopt($fch, CURLOPT_POSTFIELDS, $fdata);

            curl_exec($fch);
            $userIndex = $FCMrow['id'];

            $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point+10 WHERE user_tb_id= $userIndex";
            mysqli_query($con, $point_plus_update_sql);
            //랭킹포인트 적립 히스토리에 기록하기.
            $record_datetime = date("Y-m-d H:i:s");
            $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
point_get_user_tb_id,
how_to_get_point,
how_many_point,
record_datetime)
VALUES (
  $userIndex,
  'host_group_done',
  '10',
  '$record_datetime'
  )";
        }
        echo "true";
    } else {
        echo "false";
    }
}
