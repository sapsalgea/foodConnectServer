<?php
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");
//파일 받기
$profileImage = $_FILES['uploaded_file']['name'];
$thumbnail_chatImage = $_FILES['uploaded_file1']['name'];

$user_tb_id = $_POST['user_tb_id'];
$nic_name = $_POST['nic_name'];
$introduction = $_POST['introduction'];

$updatepath = 'images/profile_image/';
$thumbnailupdatepath = 'images/profile_image_thumbnail/';


// 저장할 경로
$file_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/profile_image/';
$tempData = $_FILES['uploaded_file']['tmp_name'];
$name = basename($_FILES["uploaded_file"]["name"]);

$getUserImagePathSQL = "SELECT profile_image ,thumbnail_image FROM user_tb WHERE id = $user_tb_id LIMIT 1";
$getUserImagePathResult = mysqli_query($con, $getUserImagePathSQL);

$getUserImagePathRow = $getUserImagePathResult->fetch_assoc();


if (file_exists('../' . $getUserImagePathRow['profile_image'])) {

    unlink('../' . $getUserImagePathRow['profile_image']);
}

if (file_exists('../' . $getUserImagePathRow['thumbnail_image'])) {
    unlink('../' . $getUserImagePathRow['thumbnail_image']);
}
$request_str = "";


if (isset($profileImage)) {

    if (move_uploaded_file($tempData, $file_dir . $name)) {


        $request_str = $request_str . "원본이미지업로드성공";
    } else {


        $request_str = $request_str . "원본이미지업로드실패";
    }
} else {

    $request_str = $request_str . "원본이미지업로드실패";
}







// 저장할 경로
$thumbnail_file_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/profile_image_thumbnail/';
$thumbnail_tempData = $_FILES['uploaded_file1']['tmp_name'];
$thumbnail_name = basename($_FILES["uploaded_file1"]["name"]);






if (isset($thumbnail_chatImage)) {

    if (move_uploaded_file($thumbnail_tempData, $thumbnail_file_dir . $thumbnail_name)) {


        $request_str = $request_str . "썸네일이미지업로드성공";
    } else {


        $request_str = $request_str . "썸네일이미지업로드실패";
    }
} else {

    $request_str = $request_str . "썸네일이미지업로드실패";
}






$sql = "UPDATE user_tb SET nick_name = $nic_name, introduction = $introduction , profile_image = '$updatepath$name', thumbnail_image = '$thumbnailupdatepath$thumbnail_name' WHERE id = $user_tb_id";

$rankingImageChangeSQL = "UPDATE ranking_test_user_tb SET profile_image ='$updatepath$name' , thumbnail_image = '$thumbnailupdatepath$thumbnail_name' WHERE id = $user_tb_id";

$rankingSeasonNameChangeSQL = "UPDATE ranking_season_tb SET user_tb_nicname = $nic_name WHERE user_tb_id = $user_tb_id";


$joinUserRecordTbSQL = "UPDATE join_room_record_tb SET user_nickname = $nic_name WHERE user_index = $user_tb_id";

$joinUserTbSQL = "UPDATE join_room_tb SET user_nickname = $nic_name, WHERE user_index = $user_tb_id";

$roomTbSQL = "UPDATE room_tb SET name_host = $nic_name WHERE host_index = $user_tb_id";

$groupMessageChange = "UPDATE group_message_tb SET from_user_id = $nic_name, thumbnailImage = '$updatepath$name' WHERE user_index = $user_tb_id";


mysqli_query($con, $joinUserRecordTbSQL);

mysqli_query($con, $joinUserTbSQL);

mysqli_query($con, $roomTbSQL);

mysqli_query($con, $groupMessageChange);


$senderNicNameUpdateActionAlarmTbSQL = "UPDATE action_alarm_tb SET sender_user_tb_nicname = $nic_name WHERE sender_user_tb_id = $user_tb_id";
mysqli_query($con,$senderNicNameUpdateActionAlarmTbSQL);

$commentWriterActionAlarmTbSQL = "UPDATE action_alarm_tb SET comment_writing_user_nicname = $nic_name WHERE comment_writing_user_id = $user_tb_id";
mysqli_query($con,$commentWriterActionAlarmTbSQL);




if (mysqli_query($con, $sql) && mysqli_query($con, $rankingImageChangeSQL)&& mysqli_query($con, $rankingSeasonNameChangeSQL)) {
    $request_str = $request_str . "유저정보수정성공";
} else {

    $request_str = $request_str . "유저정보수정실패";
}

mysqli_close($con);



echo $request_str;
