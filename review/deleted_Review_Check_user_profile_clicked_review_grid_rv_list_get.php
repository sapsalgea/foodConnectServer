<?php



$review_id = $_POST['review_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");









$sql = "SELECT review_deleted FROM review_tb WHERE review_id = $review_id";

$review_deleted_result = mysqli_query($con,$sql);
// print_r($result);
// var_dump($comment_row);
// echo $comment_row[0];

$num =mysqli_num_rows($review_deleted_result);
$row = $review_deleted_result->fetch_assoc();



if($num==1){

  $is_review_deleted = $row['review_deleted'];


  if($is_review_deleted==1){
      echo "true";
  }else if($is_review_deleted==0){
      echo "false";
  }
}










mysqli_close($con);
?>
