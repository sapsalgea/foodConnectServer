<?php



$review_tb_id = $_POST['review_tb_id'];
$user_tb_id = $_POST['user_tb_id'];


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");



//삭제여부
$query = "SELECT review_deleted FROM review_tb WHERE review_id = $review_tb_id";
$data = mysqli_query($con, $query);
$row = mysqli_fetch_array($data);
$review_deleted = $row[0];





$response['review_deleted'] = $review_deleted;


echo json_encode($response,JSON_UNESCAPED_UNICODE);










mysqli_close($con);
?>
