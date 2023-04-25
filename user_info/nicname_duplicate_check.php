<?php

$nick_name = $_POST['nick_name'];

$db = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($db, "set names utf8");


$sql = "SELECT COUNT(*) FROM user_tb WHERE nick_name ='$nick_name'";

$result = mysqli_query($db, $sql);
$comment_row = mysqli_fetch_array($result);

// print_r($result) ;
// var_dump($comment_row) ;
// echo $comment_row[0];

mysqli_close($db);

if($comment_row[0] == 1){

echo "true";}
else{
echo "false";}

?>
