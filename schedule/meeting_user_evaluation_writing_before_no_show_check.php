<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$room_id = $_POST['room_id'];
$user_tb_id =$_POST['user_tb_id'];

$noshow_user_state_sql = "SELECT * FROM join_room_tb WHERE room_id = $room_id AND user_index = $user_tb_id";

if($result = mysqli_query($con,$noshow_user_state_sql)){
  $noshow_user_state_row = mysqli_fetch_assoc($result);

  if($noshow_user_state_row['no_show'] == 1){
        $response['isNoShow'] = 1;
        $response['success'] = true;

        echo json_encode($response);
  }

  else {
    $response['isNoShow'] = 0;
    $response['success'] = true;

    echo json_encode($response);
  }
}else {
  $response['isNoShow'] = 0;
  $response['success'] = false;

  echo json_encode($response);
}






mysqli_close($con);





?>
