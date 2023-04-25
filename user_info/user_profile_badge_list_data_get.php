<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$user_tb_id = $_POST['user_tb_id'];
$user_tb_nicname = $_POST['user_tb_nicname'];

$badgeList = array();



//1번 이상 참여 첫걸음 뱃지
$query = "SELECT COUNT(*) FROM join_room_tb WHERE user_nickname = $user_tb_nicname";
$data = mysqli_query($con, $query);
$count_row = mysqli_fetch_array($data);
$join_count = $count_row[0];


$join_count_check = 0;

if($join_count>= 1){
  $join_count_check = 1;
}


$array = array(
  "badge_name" =>"참가의 즐거움",
  "badge_achieve_check" => $join_count_check,
  "badge_achieve_goal_image" =>"images/badge/first_join_1.png",
  "badge_fail_goal_image" =>"images/badge/lock.png",
  "how_to_achieve_goal" =>"모임에 1회 이상 참가하세요.",
  "congratulations_message" =>"당신은 첫발을 내딛었습니다."

);


array_push($badgeList,$array);


//1번 이상 호스트 경험

$query = "SELECT COUNT(*) FROM room_tb WHERE name_host = $user_tb_nicname";
$data = mysqli_query($con, $query);
$count_row = mysqli_fetch_array($data);
$host_count = $count_row[0];


$host_count_check = 0;

if($host_count>= 1){
  $host_count_check = 1;
}


$array = array(
  "badge_name" =>"리더의 시작",
  "badge_achieve_check" => $host_count_check,
  "badge_achieve_goal_image" =>"images/badge/first_host_2.png",
  "badge_fail_goal_image" =>"images/badge/lock.png",
  "how_to_achieve_goal" =>"호스트로 모임을 1회 이상 완료하세요.",
  "congratulations_message" =>"모임 개최를 성공적으로 마무리하였습니다."

);


array_push($badgeList,$array);




//리뷰 1회이상 작성

$query = "SELECT COUNT(*) FROM review_tb WHERE writer_user_tb_id = $user_tb_id";
$data = mysqli_query($con, $query);
$count_row = mysqli_fetch_array($data);
$review_count = $count_row[0];


$review_count_check = 0;

if($review_count>= 1){
  $review_count_check = 1;
}


$array = array(
  "badge_name" =>"초보 리뷰어",
  "badge_achieve_check" => $review_count_check,
  "badge_achieve_goal_image" =>"images/badge/first_review_1.png",
  "badge_fail_goal_image" =>"images/badge/lock.png",
  "how_to_achieve_goal" =>"리뷰를 1회 이상 작성하세요.",
  "congratulations_message" =>"처음으로 내 역사의 일부분을 남겼어요!"

);


array_push($badgeList,$array);





$sql = "SELECT * FROM user_evaluation_record WHERE user_tb_id = $user_tb_id";


$result = mysqli_query($con, $sql);

if($result){




  //모임원 평가 점수 결과로 뱃지 획득여부확인
  $row = $result->fetch_assoc();

  /*유쾌한 타입*/
  $badge_delightful_type_check = 0;

  if($row['delightful_type']>= 1){
    $badge_delightful_type_check = 1;
  }

  $array = array(
    "badge_name" =>"유쾌함",
    "badge_achieve_check" => $badge_delightful_type_check,
    "badge_achieve_goal_image" =>"images/badge/delightful_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 유쾌함 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"당신은 유쾌한 사람이군요!"

  );


  array_push($badgeList,$array);


  /*고독한미식가 타입*/
  $badge_gourmet_type_check = 0;

  if($row['gourmet_type']>= 1){
    $badge_gourmet_type_check = 1;
  }

  $array = array(
    "badge_name" =>"고독한미식가",
    "badge_achieve_check" =>$badge_gourmet_type_check,
    "badge_achieve_goal_image" =>"images/badge/gourmet_type_1.jpg",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 고독한미식가 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"조용히 음미하며 식사하는 것을 좋아합니다!"

  );


  array_push($badgeList,$array);



  /*재미있음 타입*/
  $badge_funny_type_check = 0;
  if($row['funny_type']>= 1){
    $badge_funny_type_check = 1;
  }


  $array = array(
    "badge_name" =>"재미있음",
    "badge_achieve_check" =>$badge_funny_type_check,
    "badge_achieve_goal_image" =>"images/badge/funny_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 재미있음 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"저와 함께 있으면 즐거워요!"

  );

  array_push($badgeList,$array);



  /*시끄러움 타입*/

  $badge_noisy_type_check = 0;
  if($row['noisy_type']>= 1){
      $badge_noisy_type_check = 1;
  }


  $array = array(
    "badge_name" =>"시끄러움",
    "badge_achieve_check" =>$badge_noisy_type_check,
    "badge_achieve_goal_image" =>"images/badge/noisy_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 시끄러움 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"저는 분위기를 떠들썩하게 만들 수 있습니다!"

  );


  array_push($badgeList,$array);


  /*무뚝뚝한 타입*/

  $badge_curt_type_check = 0;

  if($row['curt_type']>= 1){
    $badge_curt_type_check = 1;
  }

  $array = array(
    "badge_name" =>"무뚝뚝",
    "badge_achieve_check" =>$badge_curt_type_check,
    "badge_achieve_goal_image" =>"images/badge/curt_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 무뚝뚝 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"가까이 다가와주세요. 사실 착한사람입니다."

  );


  array_push($badgeList,$array);



  /*맛잘알타입*/

  $badge_food_smart_type_check = 0;
  if($row['food_smart_type']>= 1){
      $badge_food_smart_type_check = 1;
  }

  $array = array(
    "badge_name" =>"맛잘알",
    "badge_achieve_check" =>$badge_food_smart_type_check,
    "badge_achieve_goal_image" =>"images/badge/food_smart_type_1.jpg",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 맛잘알 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"음식에 대한 해박한 지식을 갖고 있습니다!"

  );


  array_push($badgeList,$array);



  /*친화력갑타입*/

  $badge_sociability_type_check = 0;

  if($row['sociability_type']>= 1){
      $badge_sociability_type_check = 1;
  }


  $array = array(
    "badge_name" =>"친화력갑",
    "badge_achieve_check" =>$badge_sociability_type_check,
    "badge_achieve_goal_image" =>"images/badge/sociability_type_2.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 친화력갑 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"알고 보니 저는 인싸력이 강한 사람이었습니다!"

  );


  array_push($badgeList,$array);



  /*미소지기타입*/

  $badge_sociability_type_check = 0;

  if($row['smile_type']>= 1){

    $badge_sociability_type_check = 1;
  }


  $array = array(
    "badge_name" =>"미소지기",
    "badge_achieve_check" =>$badge_sociability_type_check,
    "badge_achieve_goal_image" =>"images/badge/smile_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 미소지기 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"쑥스러워서 그래요. 친해지면 말이 많아져요!"

  );


  array_push($badgeList,$array);




  /*부담스러움 타입*/

  $badge_sociability_type_check = 0;

  if($row['uncomfortable_type']>= 1){
    $badge_sociability_type_check = 1;

  }

  $array = array(
    "badge_name" =>"부담스러움",
    "badge_achieve_check" =>$badge_sociability_type_check,
    "badge_achieve_goal_image" =>"images/badge/uncomfortable_type_1.png",
    "badge_fail_goal_image" =>"images/badge/lock.png",
    "how_to_achieve_goal" =>"모임원 평가에서 부담스러움 평가를 10회 이상 받으세요.",
    "congratulations_message" =>"저에게 가까이 오실 수 있겠습니까?"

  );


  array_push($badgeList,$array);













        $response['success'] = "true";
        $response['badgeList'] = $badgeList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['badgeList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
