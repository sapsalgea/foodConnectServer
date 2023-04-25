<?php

$user_tb_id = $_POST['user_tb_id'];

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");



//랭킹 사용하는 변수
$ranking_tb_id = "";
$tier = "";
//$now_season = 0;
$rank = "";
$tier_image ="";
$rank_point = "";
$number = "";


//탈퇴한 유저인지 아닌지 체크한다.

$sql = "SELECT * FROM user_tb WHERE id = $user_tb_id";
$result = mysqli_query($con, $sql);
$row = $result->fetch_assoc();

//만약 탈퇴하지 않은 유저라면..
if($row['account_delete'] ==0){



          $total_user_count_sql ="SELECT COUNT(*)FROM ranking_season_tb";

          $total_user_count_result = mysqli_query($con, $total_user_count_sql);

          $count_row = mysqli_fetch_array($total_user_count_result);
          $total_user_count = $count_row[0];


          $diamond = $total_user_count*0.05;
          $diamond = floor($diamond);
          $master = $total_user_count*0.02;
          $master = floor($master);
          $challenger = $total_user_count*0.005;
          $challenger = floor($challenger);


          //랭킹 담는 테이블
          $rankingList = array();

          // 내아이디 랭킹 가져오기

          $sql = "SET @rownum:=0";
          $result = mysqli_query($con, $sql);

          $sql = "SELECT * FROM (SELECT *,(@rownum:=@rownum+1) AS number, (SELECT COUNT( * ) +1 FROM ranking_season_tb WHERE season_point > t.season_point) AS rank  FROM ranking_season_tb  AS t  ORDER BY season_point DESC) AS k WHERE user_tb_id = $user_tb_id";





          $result = mysqli_query($con, $sql);



          if($result){



          $ranking_row = $result->fetch_assoc();






            $user_tb_id = $ranking_row['user_tb_id'];

            $rank_point = $ranking_row['season_point'];




            $top_tier_check_sql ="SELECT COUNT(*) FROM ranking_season_tb WHERE season_point>$rank_point";

            $top_tier_check_result = mysqli_query($con, $top_tier_check_sql);

            $top_tier_check_row = mysqli_fetch_array($top_tier_check_result);

            $top_tier_rank = $top_tier_check_row[0]+1;








            $tier = "스톤";
            $tier_image = "ranking/tier_badge_image/stone_1.png";

            if($top_tier_rank<=$challenger && $rank_point>3000){
              $tier = "챌린저";
              $tier_image = "ranking/tier_badge_image/challenger_1.png";
            }
            else if($top_tier_rank<=$master && $rank_point>3000){
              $tier = "마스터";
              $tier_image = "ranking/tier_badge_image/master_1.png";
            }

            else if($top_tier_rank<=$diamond && $rank_point>3000){
              $tier = "다이아몬드";
              $tier_image = "ranking/tier_badge_image/diamond_1.png";
            }

            else if($rank_point <200){
              $tier = "스톤";
              $tier_image = "ranking/tier_badge_image/stone_1.png";
            } else if($rank_point <600){
              $tier = "브론즈";
              $tier_image = "ranking/tier_badge_image/bronze_1.png";
            } else if($rank_point <1200){
              $tier = "실버";
              $tier_image = "ranking/tier_badge_image/silver_1.png";
            } else if($rank_point <2000){
              $tier = "골드";
              $tier_image = "ranking/tier_badge_image/gold_1.png";
            } else if($rank_point >=2000){
              $tier = "플래티넘";
              $tier_image = "ranking/tier_badge_image/platinum_1.png";
            }

            $user_info_sql = "SELECT * from ranking_test_user_tb WHERE id = $user_tb_id";

            $user_info_result = mysqli_query($con, $user_info_sql);

            if($user_info_result){
              $user_info_row = $user_info_result->fetch_assoc();
              $profile_image=$user_info_row['thumbnail_image'];
            }




            $number = $ranking_row['number'];
            $ranking_tb_id = $ranking_row['season_ranking_tb_id'];
            $rank = $ranking_row['rank'];






        }


  //노쇼개수
  $no_show_count_query = "SELECT COUNT(*) FROM join_room_tb WHERE user_index = $user_tb_id AND no_show = 1";
  $no_show_count_data = mysqli_query($con, $no_show_count_query);
  $no_show_count_row = mysqli_fetch_array($no_show_count_data);
  $no_show_count = $no_show_count_row[0];




  //리뷰 개수
  $query = "SELECT COUNT(*) FROM review_tb WHERE writer_user_tb_id = $user_tb_id AND review_deleted = 0";
  $data = mysqli_query($con, $query);
  $row = mysqli_fetch_array($data);
  $review_count = $row[0];



  $sql = "SELECT * FROM user_tb WHERE id = $user_tb_id";

  $result = mysqli_query($con, $sql);
  // print_r($result);
  // var_dump($comment_row);
  // echo $comment_row[0];

  $num =mysqli_num_rows($result);
  $row = $result->fetch_assoc();
  $response = array();



  if($num==1){
    $response['success'] = true;
    $response['id'] = $row['id'];
    $response['user_id'] = $row['user_id'];
    $response['social_login_type'] = $row['social_login_type'];
    $response['nick_name'] = $row['nick_name'];
    $response['profile_image'] = $row['profile_image'];
    $response['thumbnail_image'] = $row['thumbnail_image'];
    $response['birth_year'] = $row['birth_year'];
    $response['gender'] = $row['gender'];
    $response['phone_number'] = $row['phone_number'];
    $response['introduction'] = $row['introduction'];
    $response['review_count'] = $review_count;
    $response['account_delete'] = $row['account_delete'];


    //랭킹 관련 데이터
    $response['ranking_tb_id'] = $ranking_tb_id;
    $response['tier'] = $tier;
    $response['rank'] = $rank;
    $response['tier_image'] = $tier_image;
    $response['rank_point'] = $rank_point;
    $response['number'] = $number;

    //노쇼 개수
    $response['no_show_count'] = $no_show_count;





    echo json_encode($response,JSON_UNESCAPED_UNICODE);
    //var_dump($response);
  }else {
    $response['success'] = false;
  }

}else {
  // 탈퇴한 유저라면..

  $response = array();

  $response['success'] = true;
  $response['account_delete'] = 1;






  echo json_encode($response,JSON_UNESCAPED_UNICODE);


}








mysqli_close($con);
?>
