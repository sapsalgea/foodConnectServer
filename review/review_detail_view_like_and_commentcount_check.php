<?php



$review_tb_id = $_POST['review_tb_id'];
$user_tb_id = $_POST['user_tb_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


//좋아요 클릭 여부
//좋아요 개수
$query = "SELECT COUNT(*) FROM review_like_clicked_user WHERE review_tb_id = $review_tb_id AND user_tb_id = $user_tb_id";
$data = mysqli_query($con, $query);
$row = mysqli_fetch_array($data);


if($row[0] ==0){
  $islikeClicked = false;
}else if($row[0] ==1){

  $islikeClicked = true;
}




//좋아요 개수
$like_count_sql = "SELECT * FROM review_tb WHERE review_id = $review_tb_id";
$like_count_row = mysqli_fetch_assoc(mysqli_query($con,$like_count_sql));
$like_count = $like_count_row['like_count'];

//댓글 개수
$query = "SELECT COUNT(*) FROM review_comment WHERE review_id = $review_tb_id AND deleteCheck = 0";
$data = mysqli_query($con, $query);
$row = mysqli_fetch_array($data);
$comment_count = $row[0];


//삭제여부
$query = "SELECT review_deleted FROM review_tb WHERE review_id = $review_tb_id";
$data = mysqli_query($con, $query);
$row = mysqli_fetch_array($data);
$review_deleted = $row[0];






$response['islikeClicked'] = $islikeClicked;
$response['like_count'] = $like_count;
$response['comment_count'] = $comment_count;
$response['review_deleted'] = $review_deleted;


echo json_encode($response,JSON_UNESCAPED_UNICODE);










mysqli_close($con);
?>
