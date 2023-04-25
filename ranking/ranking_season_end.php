<?php

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");





//시즌 구하기

// $today_date = date("Y-m-d");
//
// $today_date_split = explode( '-', $today_date );
//
//
// echo $today_date_split[0];
//
// $year_result = $today_date_split[0] - 2021;
//
// echo $year_result;
//
// if($today_date_split[1]>0 && $today_date_split[1]<7 ){
//   $month_result = 1;
// }else {
//   $month_result = 2;
// }
//
// $now_season = $year_result + $month_result;
//
// echo "현재시즌:".$now_season;


$now_season = 13;

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


$query_IsTrue = 0;





//전체기록 가져오기
$sql = "SET @rownum:=0";
$result = mysqli_query($con, $sql);

$sql = "SELECT * FROM (SELECT *,(@rownum:=@rownum+1) AS number, (SELECT COUNT( * ) +1 FROM ranking_season_tb WHERE season_point > t.season_point) AS rank  FROM ranking_season_tb  AS t  ORDER BY season_point DESC) AS k ORDER BY NUMBER desc";




        $result = mysqli_query($con, $sql);

        $result_copy = $result;






        if($result){



        //시즌 종료 - 최종 데이터 저장
        while($row = $result->fetch_assoc()){






          $user_tb_id = $row['user_tb_id'];
          $rank_point = $row['season_point'];




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


          $user_tb_nicname = $row['user_tb_nicname'];
          $season_rank = $row['rank'];
          $number = $row['number'];


          $insert_sql = "INSERT INTO before_season_tier_tb (season_name, user_tb_id, user_nicname, season_rank, season_count_number,  ranking_point, user_tier) VALUES ($now_season,$user_tb_id,'$user_tb_nicname',$season_rank,$number,$rank_point,'$tier')";

          mysqli_query($con,$insert_sql);
            // //방하나의 객체가될 배열
            // $array = array(
            //
            //   "number" => $row['number'],
            //   "ranking_tb_id" => $row['season_ranking_tb_id'],
            //   "user_tb_id" => $user_tb_id,
            //   "user_tb_nicname" => $row['user_tb_nicname'],
            //   "season_name" => $row['season_name'],
            //   "rank_point" => $rank_point,
            //   "rank" => $row['rank'],
            //   "profile_image" => $profile_image,
            //   "tier" => $tier,
            //   "tier_image" => $tier_image
            //
            //
            // );
            //
            // //rankingList 배열에 배열저장
            // array_push($rankingList,$array);

            //포인트 초기화
            $reset_point = 0;


            if($rank_point>3000){
              $reset_point = 2200;
            }else if($rank_point>=2000){
              $reset_point = 1600;
            }else if($rank_point>=1200){
              $reset_point = 800;
            }else if($rank_point>=600){
              $reset_point = 400;
            }else if($rank_point>=200){
              $reset_point = 100;
            }else {
              $reset_point = 0;
            }


            $reset_update_sql = "UPDATE ranking_season_tb SET season_point = $reset_point WHERE user_tb_id=$user_tb_id";
            mysqli_query($con,$reset_update_sql);


            $query_IsTrue = $query_IsTrue+1;


        }



        // $response['success'] = "true";
        // $response['rankingList'] = $rankingList;
        //
        // echo json_encode($response);
      }






if($query_IsTrue == $total_user_count){
  echo "true";
}else {
  echo "false";
}




mysqli_close($con);

?>
