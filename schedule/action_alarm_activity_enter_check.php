<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$user_tb_id = $_POST['user_tb_id'];






$sql = "UPDATE action_alarm_tb SET receiver_activity_enter_check = 1 WHERE receiver_user_tb_id = $user_tb_id AND receiver_activity_enter_check = 0";



        $result = mysqli_query($con, $sql);



        if($result){
          $response['success'] = "true";
          echo json_encode($response);

        }else{
          $response['success'] = "false";
          echo json_encode($response);
        }





mysqli_close($con);



?>
