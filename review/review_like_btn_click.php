<?php

$sendTargetUserTable_id = $_POST['sendTargetUserTable_id'];
$like_click_user_nicname = $_POST['like_click_user_nicname'];
$which_text_choose = $_POST['which_text_choose'];



$what_click_review_tb_id = $_POST['what_click_review_tb_id'];
$my_user_tb_id = $_POST['my_user_tb_id'];
$my_user_tb_user_id = $_POST['my_user_tb_user_id'];

$db = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($db, "set names utf8");





//삭제여부
$query = "SELECT review_deleted FROM review_tb WHERE review_id = $what_click_review_tb_id";
$data = mysqli_query($db, $query);
$row = mysqli_fetch_array($data);
$review_deleted = $row[0];


$sql = "SELECT * FROM review_like_clicked_user WHERE review_tb_id ='$what_click_review_tb_id' AND user_tb_id = '$my_user_tb_id'";



$result_count = mysqli_query($db,$sql);
$count = mysqli_num_rows($result_count);

if($count == 0){

  $now_date = date("Y-m-d H:i:s");
  $sql = "INSERT INTO review_like_clicked_user (review_tb_id, user_tb_id, user_tb_user_id, clicked_date) VALUES ($what_click_review_tb_id, $my_user_tb_id, $my_user_tb_user_id, '$now_date')";
  mysqli_query($db,$sql);

  $sql = "UPDATE review_tb SET like_count=like_count+1 WHERE review_id='$what_click_review_tb_id'";
  mysqli_query($db,$sql);





  $result=mysqli_query($db,"SELECT like_count FROM review_tb WHERE review_id='$what_click_review_tb_id'");
  $row = mysqli_fetch_assoc($result);

  $response['heart_making'] = true;
  $response['how_many_like_count'] = $row['like_count'];
  $response['success'] = true;
  $response['review_deleted'] = $review_deleted;
  $response['isDoubleLikeButtonClicked'] = false;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);



  //랭킹포인트 적립
  $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point+1 WHERE user_tb_id= $sendTargetUserTable_id";
  mysqli_query($db,$point_plus_update_sql);



  //랭킹포인트 적립 히스토리에 기록하기.
  $record_datetime = date("Y-m-d H:i:s");
  $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
  point_get_user_tb_id,
  how_to_get_point,
  how_many_point,
  record_datetime)
  VALUES (
    $sendTargetUserTable_id,
    'someone_like_btn_click',
    '1',
    '$record_datetime'
    )";
  mysqli_query($db,$ranking_point_record_insert_sql);

  //리뷰 테이블에 추가포인트 기록하기
  $review_tb_point_plus_update_sql = "UPDATE review_tb SET ranking_extra_point=ranking_extra_point+1 WHERE review_id= $what_click_review_tb_id";
  mysqli_query($db,$review_tb_point_plus_update_sql);









  //본인 것에 좋아요를 눌렀을때는 테이블에 들어가지않는다.
  if($sendTargetUserTable_id != $my_user_tb_id){
    //알림목록 테이블에 좋아요 데이터를 넣는다
    $sql = "INSERT INTO action_alarm_tb (
    receiver_user_tb_id,
    action_type,
    sender_user_tb_id,
    sender_user_tb_nicname,
    which_text_choose,
    review_id,
    action_datetime
    )

    VALUES (
      $sendTargetUserTable_id,
      'review_like_btn',
      $my_user_tb_id,
      $like_click_user_nicname,
      $which_text_choose,
      $what_click_review_tb_id,
      '$now_date'
    )";

    $result = mysqli_query($db,$sql);


    //좋아요 누른 사람의 fcm 토큰 가져오기
    $user_token_query = "SELECT user_token FROM user_tb WHERE id = $sendTargetUserTable_id";
    $user_token_data = mysqli_query($db, $user_token_query);
    $user_token_row = mysqli_fetch_array($user_token_data);
    $user_token = $user_token_row[0];





    // 부모댓글 - FCM 전송


    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
      $header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
      $data = json_encode(array(
          "to" =>$user_token,
          "priority" => "high",
          "data" => array(
              "title"   => $which_text_choose." 글을 ".$comment_writer_nicname."님이 좋아합니다",
              "body" => "",
              "islikeBtnClick" => true,
              "review_id" => $what_click_review_tb_id
          )
      ));






      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

      curl_exec($ch);


  }






}



//만약 하트취소를 하고 싶으면 본 else문을 지우고 하단의 else문을 사용할 것.
//여기에 else문은 좋아요를 2번이상 클릭했을때, 알림문구를 띄어주기 위함이다.
else{

  $result=mysqli_query($db,"SELECT like_count FROM review_tb WHERE review_id='$what_click_review_tb_id'");
  $row = mysqli_fetch_assoc($result);


  $response['heart_making'] = true;
  $response['how_many_like_count'] = $row['like_count'];
  $response['success'] = true;
  $response['isDoubleLikeButtonClicked'] = true;
  echo json_encode($response,JSON_UNESCAPED_UNICODE);

}



// else{
//
//   $sql = "DELETE FROM review_like_clicked_user  WHERE review_tb_id ='$what_click_review_tb_id' AND user_tb_id = '$my_user_tb_id'";
//   mysqli_query($db,$sql);
//
//   $sql = "UPDATE review_tb SET like_count=like_count-1 WHERE review_id='$what_click_review_tb_id'";
//   mysqli_query($db,$sql);
//
//   $result=mysqli_query($db,"SELECT like_count FROM review_tb WHERE review_id='$what_click_review_tb_id'");
//   $row = mysqli_fetch_assoc($result);
//
//
//   $response['heart_making'] = false;
//   $response['how_many_like_count'] = $row['like_count'];
//   $response['success'] = true;
//   $response['review_deleted'] = $review_deleted;
//   echo json_encode($response,JSON_UNESCAPED_UNICODE);
//
//
//
// }








mysqli_close($con);
?>
