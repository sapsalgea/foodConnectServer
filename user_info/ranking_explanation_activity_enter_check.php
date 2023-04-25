<?php


$my_user_tb_id = $_POST['my_user_tb_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");







$sql = "UPDATE user_tb SET ranking_explanation_check = 1 WHERE id = $my_user_tb_id";
$result = mysqli_query($con,$sql);



if($result == true){
  echo "true";
}else {
  echo "false";
}






mysqli_close($con);
?>
