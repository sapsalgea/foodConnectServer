<?php

$review_id = $_POST['review_id'];
$my_user_tb_id = $_POST['my_user_tb_id'];
$what_click_comment_id = $_POST['what_click_comment_id'];

$parentOrChild = $_POST['parentOrChild'];
$groupNum = $_POST['groupNum'];

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");





//삭제여부
$query = "SELECT review_deleted FROM review_tb WHERE review_id = $review_id";
$data = mysqli_query($con, $query);
$row = mysqli_fetch_array($data);
$review_deleted = $row[0];



//댓글 개수
$query = "SELECT COUNT(*) FROM review_comment LEFT JOIN user_tb ON review_comment.writing_user_id = user_tb.id WHERE review_id = $review_id AND groupNum = $groupNum AND deleteCheck = 0 AND account_delete = 0";
$data = mysqli_query($con, $query);
$count_row = mysqli_fetch_array($data);
$comment_count = $count_row[0];


if($parentOrChild == 0){

  $sql = "UPDATE review_tb SET comment_count=comment_count-$comment_count WHERE review_id=$review_id";
  mysqli_query($con,$sql);

  $sql = "UPDATE review_comment SET deleteCheck=1 WHERE groupNum = $groupNum AND review_id=$review_id";
  $result=mysqli_query($con,$sql);




}else {

  //본인의 아이디로 지우는 것이 맞는지 확실히 하기 위함.
  $sql = "UPDATE review_comment SET deleteCheck=1 WHERE writing_user_id = $my_user_tb_id AND comment_id=$what_click_comment_id";
  $result=mysqli_query($con,$sql);


  $sql = "UPDATE review_tb SET comment_count=comment_count-1 WHERE review_id=$review_id";
  mysqli_query($con,$sql);
}




if($result == true){
  $response['success'] = "true";
  $response['review_deleted'] = $review_deleted;

echo json_encode($response);
}else {
  $response['success'] = "false";
  $response['review_deleted'] = $review_deleted;

echo json_encode($response);
}






mysqli_close($con);
?>
