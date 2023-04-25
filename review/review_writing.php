<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$room_tb_id = $_POST['room_tb_id'];
$writer_user_tb_id = $_POST['writer_user_tb_id'];
$writer_uid = $_POST['writer_uid'];
$writer_nicname = $_POST['writer_nicname'];
$restaurant_address = $_POST['restaurant_address'];
$restaurant_name = $_POST['restaurant_name'];
$reporting_date = date("Y-m-d H:i:s");
$appointment_day = $_POST['appointment_day'];
$appointment_time = $_POST['appointment_time'];
$review_description = $_POST['review_description'];
$rating_star_taste = $_POST['rating_star_taste'];
$rating_star_service = $_POST['rating_star_service'];
$rating_star_clean = $_POST['rating_star_clean'];
$rating_star_interior = $_POST['rating_star_interior'];
// $review_picture_0 = $_POST['review_picture_1'];
// $review_picture_1 = $_POST['review_picture_2'];
// $review_picture_2 = $_POST['review_picture_3'];





// // 파일 받기
// $profile_File =$_FILES["itemphoto0"]["name"];
//
//
// // 저장할 경로
// $file_name =$_SERVER['DOCUMENT_ROOT']. './images/review_image/';
// $tempData = $_FILES['itemphoto0']['tmp_name'];
// $name = basename($_FILES["itemphoto0"]["name"]);
//
// // // 임시폴더에서  ->  경로 이동 .파일이름
//
// move_uploaded_file($tempData, $file_name.$name);


$file= $_FILES['itemphoto0'];

//이미지 파일을 영구보관하기 위해
//이미지 파일의 세부정보 얻어오기
$srcName= $file['name'];
$tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
//임시 저장소 이미지를 원하는 폴더로 이동
$dstName= "upload/".date('Ymd_his').$srcName;
$result=move_uploaded_file($tmpName, $dstName);
$review_picture_0 = "review/".$dstName;





if($_FILES['itemphoto1'] != null){
  $file= $_FILES['itemphoto1'];

  //이미지 파일을 영구보관하기 위해
  //이미지 파일의 세부정보 얻어오기
  $srcName= $file['name'];
  $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp

  //임시 저장소 이미지를 원하는 폴더로 이동
  $dstName= "upload/".date('Ymd_his').$srcName;


  $result=move_uploaded_file($tmpName, $dstName);


  $review_picture_1 = "review/".$dstName;
}





if($_FILES['itemphoto2'] != null){
  $file= $_FILES['itemphoto2'];

  //이미지 파일을 영구보관하기 위해
  //이미지 파일의 세부정보 얻어오기
  $srcName= $file['name'];
  $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp

  //임시 저장소 이미지를 원하는 폴더로 이동
  $dstName= "upload/".date('Ymd_his').$srcName;


  $result=move_uploaded_file($tmpName, $dstName);


  $review_picture_2 = "review/".$dstName;

}
//
//
// // 파일 받기
// $profile_File =$_FILES['itemphoto1']['name'];
//
//
// // 저장할 경로
// $file_name =$_SERVER['DOCUMENT_ROOT']. '/review/upload/';
// $tempData = $_FILES['itemphoto1']['tmp_name'];
// $name = basename($_FILES["itemphoto1"]["name"]);
//
//
//
// // // 임시폴더에서  ->  경로 이동 .파일이름
//
// move_uploaded_file($tempData, $file_name.$name);
//
// // 파일 받기
// $profile_File =$_FILES['itemphoto2']['name'];
//
//
// // 저장할 경로
// $file_name =$_SERVER['DOCUMENT_ROOT']. '/review/upload/';
// $tempData = $_FILES['itemphoto2']['tmp_name'];
// $name = basename($_FILES["itemphoto2"]["name"]);
//
//
//
// // // 임시폴더에서  ->  경로 이동 .파일이름
//
// move_uploaded_file($tempData, $file_name.$name);
//
//





// $room_tb_id = 5;
// $writer_uid ='"ddss"';
// $writer_nicname = '"ddss"';
// $restaurant_address = '"ddss"';
// $restaurant_name ='"ddss"';
// $reporting_date = 'now()';
// $appointment_day = '"2021-05-18"';
// $appointment_time = 'now()';
// $review_description = '"ddss"';
// $rating_star_taste = 5;
// $rating_star_service =5;
// $rating_star_clean = 5;
// $rating_star_interior =5;
// $review_picture_0 = '"ddss"';
// $review_picture_1 = '"ddss"';
// $review_picture_2 ='"ddss"';

$sql = "INSERT INTO review_tb (
room_tb_id,
writer_user_tb_id,
writer_uid,
writer_nicname,
restaurant_address,
restaurant_name,
reporting_date,
appointment_day,
appointment_time,
review_description,
rating_star_taste,
rating_star_service,
rating_star_clean,
rating_star_interior,
review_picture_0,
review_picture_1,
review_picture_2)
VALUES (
  $room_tb_id,
  $writer_user_tb_id,
  $writer_uid,
  $writer_nicname,
  $restaurant_address,
  $restaurant_name,
  '$reporting_date',
  $appointment_day,
  $appointment_time,
  $review_description,
  $rating_star_taste,
  $rating_star_service,
  $rating_star_clean,
  $rating_star_interior,
  '$review_picture_0',
  '$review_picture_1',
  '$review_picture_2')";


$result = mysqli_query($con,$sql);

$insert_id=mysqli_insert_id($con);

// $file= $_FILES['itemphoto'];
//
// //이미지 파일을 영구보관하기 위해
// //이미지 파일의 세부정보 얻어오기
// $srcName= $file['name'];
// $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
//
// //임시 저장소 이미지를 원하는 폴더로 이동
// $dstName= "upload/".date('Ymd_his').$srcName;
//
//
// $result=move_uploaded_file($tmpName, $dstName);




$a = mysqli_error($con);






if($result){

    //리뷰 작성완료 표시
    $review_evaluation_number_update_sql = "UPDATE join_room_tb SET review_result = 1 WHERE user_nickname = $writer_nicname AND room_id = $room_tb_id";
    $review_evaluation_number_update_result = mysqli_query($con, $review_evaluation_number_update_sql);


    //랭킹포인트 적립
    $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point+10 WHERE user_tb_id= $writer_user_tb_id";
    mysqli_query($con,$point_plus_update_sql);



    //랭킹포인트 적립 히스토리에 기록하기.
    $record_datetime = date("Y-m-d H:i:s");


    $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
    point_get_user_tb_id,
    how_to_get_point,
    how_many_point,
    record_datetime)
    VALUES (
      $writer_user_tb_id,
      'review_writing',
      '10',
      '$record_datetime'
      )";


    $ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);




    $ranking_get_sql = "SELECT * FROM ranking_season_tb WHERE user_tb_id = $writer_user_tb_id";
    $ranking_get_row = mysqli_fetch_assoc(mysqli_query($con,$ranking_get_sql));
    $season_point = $ranking_get_row['season_point'];

    $response['success'] = true;
    $response['review_id'] = $insert_id;
    $response['get_season_point'] = 10;
    $response['now_season_total_rangking_point'] = $season_point;



    echo json_encode($response);
}else{
  $response['success'] = false;
  $response['review_id'] = 0;
  $response['get_season_point'] = 0;
  $response['now_season_total_rangking_point'] = 0;


  echo json_encode($response);
}
mysqli_close($con);





?>
