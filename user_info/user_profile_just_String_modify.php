<?php


$user_tb_id = $_POST['user_tb_id'];
$nic_name = $_POST['nic_name'];
$introduction = $_POST['introduction'];





$request_str ="";



$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


$sql = "UPDATE user_tb SET nick_name = $nic_name, introduction = $introduction WHERE id = $user_tb_id";

$roomTbSQL = "UPDATE room_tb SET name_host = $nic_name WHERE host_index = $user_tb_id";

$joinUserRecordTbSQL = "UPDATE join_room_record_tb SET user_nickname = $nic_name WHERE user_index = $user_tb_id";

$joinUserTbSQL = "UPDATE join_room_tb SET user_nickname = $nic_name WHERE user_index = $user_tb_id";

$rankingSeasonNameChangeSQL = "UPDATE ranking_season_tb SET user_tb_nicname = $nic_name WHERE user_tb_id = $user_tb_id";

$rankingNameChangeSQL = "UPDATE ranking_test_user_tb SET nick_name = $nic_name WHERE id = $user_tb_id";

$groupMessageChange = "UPDATE group_message_tb SET from_user_id = $nic_name WHERE user_index = $user_tb_id";

mysqli_query($con,$joinUserRecordTbSQL);

mysqli_query($con,$joinUserTbSQL);

mysqli_query($con,$roomTbSQL);

mysqli_query($con, $rankingNameChangeSQL);

mysqli_query($con, $rankingSeasonNameChangeSQL);

mysqli_query($con, $groupMessageChange);



$senderNicNameUpdateActionAlarmTbSQL = "UPDATE action_alarm_tb SET sender_user_tb_nicname = $nic_name WHERE sender_user_tb_id = $user_tb_id";
mysqli_query($con,$senderNicNameUpdateActionAlarmTbSQL);

$commentWriterActionAlarmTbSQL = "UPDATE action_alarm_tb SET comment_writing_user_nicname = $nic_name WHERE comment_writing_user_id = $user_tb_id";
mysqli_query($con,$commentWriterActionAlarmTbSQL);


    if (mysqli_query($con, $sql)) {
        $request_str = $request_str."유저정보수정성공";

    } else {

        $request_str = $request_str."유저정보수정실패";

    }

mysqli_close($con);



echo $request_str;
