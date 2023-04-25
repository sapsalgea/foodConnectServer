<?php



$what_click_review_tb_id = $_POST['what_click_review_tb_id'];
$my_user_tb_id = $_POST['my_user_tb_id'];
$room_id = $_POST['room_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");

//본인의 아이디로 지우는 것이 맞는지 확실히 하기 위함.
$review_delete_sql = "UPDATE review_tb SET review_deleted=1 WHERE writer_user_tb_id = $my_user_tb_id AND review_id=$what_click_review_tb_id";
$review_delete_result=mysqli_query($con,$review_delete_sql);


//리뷰를 삭제 했으므로 join_room_tb 리뷰 작성 결과를 1 -> 0으로 변경
$result_update_sql = "UPDATE join_room_tb SET review_result=0 WHERE room_id = $room_id AND  user_index = $my_user_tb_id";
$result_update_result=mysqli_query($con,$result_update_sql);



//좋아요로 얻은 추가점수도 빼준다.

$extra_point_get_sql = "SELECT * FROM review_tb WHERE review_id = $what_click_review_tb_id";
$extra_point_get_row = mysqli_fetch_assoc(mysqli_query($con,$extra_point_get_sql));
$ranking_extra_point = $extra_point_get_row['ranking_extra_point'];


$minus_point = $ranking_extra_point + 10;

//랭킹포인트 차감
$point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-$minus_point WHERE user_tb_id= $my_user_tb_id";
mysqli_query($con,$point_plus_update_sql);



//랭킹포인트 적립 히스토리에 기록하기.
$record_datetime = date("Y-m-d H:i:s");


$ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
point_get_user_tb_id,
how_to_get_point,
how_many_point,
record_datetime)
VALUES (
  $my_user_tb_id,
  'review_deleted',
  -$minus_point,
  '$record_datetime'
  )";
$ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);











if($review_delete_result == true){

  $ranking_get_sql = "SELECT * FROM ranking_season_tb WHERE user_tb_id = $my_user_tb_id";
  $ranking_get_row = mysqli_fetch_assoc(mysqli_query($con,$ranking_get_sql));
  $season_point = $ranking_get_row['season_point'];

  $response['success'] = true;
  $response['minus_season_point'] = $minus_point;
  $response['now_season_total_rangking_point'] = $season_point;



  echo json_encode($response);
}else {
  $response['success'] = false;
  $response['minus_season_point'] = 0;
  $response['now_season_total_rangking_point'] = 0;



  echo json_encode($response);
}






mysqli_close($con);
?>
