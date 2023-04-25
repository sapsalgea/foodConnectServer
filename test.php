<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


$user_tb_id = $_POST['user_tb_id'];

$getUserImagePathSQL = "SELECT profile_image ,thumbnail_image FROM user_tb WHERE id = $user_tb_id LIMIT 1";
$getUserImagePathResult = mysqli_query($con,$getUserImagePathSQL);

$getUserImagePathRow = $getUserImagePathResult -> fetch_assoc();


echo $isFile = file_exists('./'.$getUserImagePathRow['profile_image']);

echo $isFile2 = file_exists('./'.$getUserImagePathRow['thumbnail_image']);

if($isFile){
    echo "있음";
}
if($isFile2){
    echo "있음2";
}
?>
