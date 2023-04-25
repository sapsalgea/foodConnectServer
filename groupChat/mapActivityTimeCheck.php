<?php 

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomId = $_POST['roomId'];

$sql = "SELECT * FROM room_tb WHERE id = $roomId";

$result = mysqli_query($con,$sql);

if($result){

    $row = $result->fetch_assoc();
    
    $nowtime = date('Y-m-d H:i:s');
    $settime = $row['appointment_day']." ".$row['appointment_time'];
    $roomStatus = (strtotime($settime)-strtotime($nowtime))/3600;    

    echo $roomStatus;

    
}else{
    echo 10000;
}