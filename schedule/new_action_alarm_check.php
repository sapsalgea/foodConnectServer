

<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$user_tb_id = $_POST['user_tb_id'];


$alarm_count_sql ="SELECT COUNT(*) FROM action_alarm_tb WHERE receiver_activity_enter_check=0 AND receiver_user_tb_id = $user_tb_id";

$alarm_count_result = mysqli_query($con, $alarm_count_sql);

$alarm_count_row = mysqli_fetch_array($alarm_count_result);

$alarm_count = $alarm_count_row[0];




        if($alarm_count_result){
          $response['success'] = "true";
          $response['alarm_count'] = $alarm_count;
          echo json_encode($response);

        }else{
          $response['success'] = "false";
          $response['alarm_count'] = -1;
          echo json_encode($response);
        }





mysqli_close($con);



?>
