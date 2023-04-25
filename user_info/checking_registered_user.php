<?php

$userid = $_POST['userId'];

$db = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($db, "set names utf8");


$sql = "SELECT * FROM user_tb WHERE user_id ='$userid'";

$result = mysqli_query($db, $sql);
// $comment_row = mysqli_fetch_array($result);

// print_r($result) ;
// var_dump($comment_row) ;
// echo $comment_row[0];
$num =mysqli_num_rows($result);
$row = $result->fetch_assoc();
$response = array();
if($num==1){

$response['success'] = true;
$response['id'] = $row['id'];
$response['userId'] = $row['user_id'];
$response['userNickname'] = $row['nick_name'];
$response['userThumbnailImage'] =$row['profile_image'];
$birth_time   = strtotime($row['birth_year']);
$now          = date('Y');
$birthday     = date('Y' , $birth_time);
$age           = $now - $birthday + 1 ;
$response['userAge'] = $age;
if($row['gender']=="MAN"){
    $response['userGender'] = "male";
}else{
    $response['userGender'] = "female";
}

$response['ranking_explanation_check'] = $row['ranking_explanation_check'];


echo json_encode($response,JSON_UNESCAPED_UNICODE);}
else{
    $response['success'] = false;
    echo json_encode($response,JSON_UNESCAPED_UNICODE);}
    mysqli_close($db);
?>
