<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$userIndex =$_POST['userIndex'];
$token = $_POST['token'];

$sql ="UPDATE user_tb SET user_token = '$token' WHERE id = $userIndex";
$result = mysqli_query($con,$sql);

if($result){

    echo "true";
}else{
    echo "false";
}