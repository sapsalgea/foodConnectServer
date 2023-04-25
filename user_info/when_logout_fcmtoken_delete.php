<?php



$user_tb_id = $_POST['user_tb_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");




//FCM 토큰 삭제
$fcm_token_del_sql = "UPDATE user_tb SET user_token = NULL WHERE id = $user_tb_id";
$fcm_token_del_result = mysqli_query($con,$fcm_token_del_sql);



if($fcm_token_del_result){




  $response['success'] = true;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);


}else {
  $response['success'] = false;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);
}








mysqli_close($con);
?>
