<?php



$user_tb_id = $_POST['user_tb_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


//계정 삭제 처리
$user_tb_account_delete_sql = "UPDATE user_tb SET account_delete = 1 WHERE id = $user_tb_id";
$account_delete_result = mysqli_query($con,$user_tb_account_delete_sql);

//user_tb의 user_id(소셜로그인토근) 변경
//기존 user_id에 +"del" 추가하기
$user_tb_user_id_update_sql = "UPDATE user_tb SET user_id = concat(user_id,'del') WHERE id = $user_tb_id";
$user_tb_user_id_update_result = mysqli_query($con,$user_tb_user_id_update_sql);

//FCM 토큰 삭제
$fcm_token_del_sql = "UPDATE user_tb SET user_token = NULL WHERE id = $user_tb_id";
$fcm_token_del_result = mysqli_query($con,$fcm_token_del_sql);

//리뷰 삭제처리
$review_delete_sql = "UPDATE review_tb SET review_deleted = 1 WHERE writer_user_tb_id = $user_tb_id";
$review_delete_result = mysqli_query($con,$review_delete_sql);


//댓글 삭제처리
$comment_delete_sql = "UPDATE review_comment SET deleteCheck = 1 WHERE writing_user_id = $user_tb_id";
$comment_delete_result = mysqli_query($con,$comment_delete_sql);

//현재 시즌 랭킹 데이터에서 제거
$season_tb_delete_sql = "DELETE FROM ranking_season_tb WHERE user_tb_id = $user_tb_id";
$season_tb_delete_result = mysqli_query($con,$season_tb_delete_sql);

//조인리스트 모두 제거
// $joinRoomDelete = "DELETE FROM join_room_tb WHERE user_index = $user_tb_id";
// $joinRoomDeleteResult = mysqli_query($con,$joinRoomDelete);

//방테이블에서 유저인덱스 제거
$roomJoinUserDelete = "UPDATE room_tb
SET join_users = IF(replace(json_search(join_users , 'one', '$user_tb_id'), '\"','') IS NOT NULL , JSON_REMOVE(
    join_users , replace(json_search(join_users, 'one', '$user_tb_id'), '\"', '')
),join_users),now_member_count = now_member_count-1 WHERE json_search(join_users , 'one', '$user_tb_id') IS NOT NULL";
$roomJoinUserDeleteResult = mysqli_query($con,$roomJoinUserDelete);

// $nowCountSql ="UPDATE room_tb SET finish = 1 WHERE now_member_count = 0 and host_index = $user_tb_id";
// $roomSqlResult = mysqli_query($con,$nowCountSql);

$roomSql ="UPDATE room_tb SET finish = 1 WHERE now_member_count = 0 and host_index = $user_tb_id";
$roomSqlResult = mysqli_query($con,$roomSql);

//신청함에서 유저 제거
$subscriptionDeleteSQL = "DELETE FROM subscription_join WHERE user_id = $user_tb_id";
$subscriptionDeleteResult = mysqli_query($con,$subscriptionDeleteSQL);



if($account_delete_result && $user_tb_user_id_update_result && $fcm_token_del_result && $review_delete_result && $comment_delete_result && $season_tb_delete_result){




  $response['success'] = true;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);


}else {
  $response['success'] = false;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);
}








mysqli_close($con);
?>
