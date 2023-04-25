<?php

$dm_room_name = $_POST['dm_room_name'];
$my_user_tb_id = $_POST['my_user_tb_id'];
$your_user_tb_id = $_POST['your_user_tb_id'];



$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


//내가 방에 소속되어있는지 확인
$sql = "SELECT COUNT(*) FROM direct_message_room_tb WHERE dm_room_name = $dm_room_name AND room_join_user_tb_id = $my_user_tb_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);


//유저가 방에 소속되어있지 않으므로 db에 기록해준다.
if($row[0] == 0){


  $nowTime = date("Y-m-d H:i:s");
  $sql = "INSERT INTO direct_message_room_tb (dm_room_name,room_join_user_tb_id,room_join_time) VALUES ($dm_room_name,$my_user_tb_id,'$nowTime')";
  $result = mysqli_query($con,$sql);

  if($result){
    echo "true";
  }else{
    echo "false";
  }
}


else if ($row[0] == 1){
  echo "true";
}


//상대방이 방에 소속되어있는지 확인

$sql = "SELECT COUNT(*) FROM direct_message_room_tb WHERE dm_room_name = $dm_room_name AND room_join_user_tb_id = $your_user_tb_id";

$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);


//유저가 방에 소속되어있지 않으므로 db에 기록해준다.
if($row[0] == 0){


  $nowTime = date("Y-m-d H:i:s");
  $sql = "INSERT INTO direct_message_room_tb (dm_room_name,room_join_user_tb_id,room_join_time) VALUES ($dm_room_name,$your_user_tb_id,'$nowTime')";
  $result = mysqli_query($con,$sql);

  if($result){
    echo "true";
  }else{
    echo "false";
  }
}


else if ($row[0] == 1){
  echo "true";
}







mysqli_close($con);

?>
