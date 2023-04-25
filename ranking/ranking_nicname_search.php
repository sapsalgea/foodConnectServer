<?php

$searchNicName = $_POST['searchNicName'];

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



$total_user_count_sql ="SELECT COUNT(*)FROM ranking_season_tb";

$total_user_count_result = mysqli_query($con, $total_user_count_sql);

$count_row = mysqli_fetch_array($total_user_count_result);
$total_user_count = $count_row[0];


$diamond = $total_user_count*0.05;
$master = $total_user_count*0.02;
$challenger = $total_user_count*0.005;








$sql = "SET @rownum:=0";
$result = mysqli_query($con, $sql);

$sql = "SELECT *,(@rownum:=@rownum+1) AS number, (SELECT COUNT( * ) +1 FROM ranking_season_tb WHERE season_point > t.season_point) AS rank  FROM ranking_season_tb  AS t WHERE  ORDER BY season_point DESC limit 50";




        $result = mysqli_query($con, $sql);

        if($result){

        $rankingList = array();

        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){






          $user_tb_id = $row['user_tb_id'];

          $rank_point = $row['season_point'];




          $top_tier_check_sql ="SELECT COUNT(*) FROM ranking_season_tb WHERE season_point>$rank_point";

          $top_tier_check_result = mysqli_query($con, $top_tier_check_sql);

          $top_tier_check_row = mysqli_fetch_array($top_tier_check_result);

          $top_tier_rank = $top_tier_check_row[0]+1;








          $tier = "스톤";

          if($top_tier_rank<=$challenger && $rank_point>3000){
            $tier = "챌린저";
          }
          else if($top_tier_rank<=$master && $rank_point>3000){
            $tier = "마스터";
          }

          else if($top_tier_rank<=$diamond && $rank_point>3000){
            $tier = "다이아몬드";
          }

          else if($rank_point <200){
            $tier = "스톤";
          } else if($rank_point <600){
            $tier = "브론즈";
          } else if($rank_point <1200){
            $tier = "실버";
          } else if($rank_point <2000){
            $tier = "골드";
          } else if($rank_point >=2000){
            $tier = "플래티넘";
          }

          $user_info_sql = "SELECT * from ranking_test_user_tb WHERE id = $user_tb_id";

          $user_info_result = mysqli_query($con, $user_info_sql);

          if($user_info_result){
            $user_info_row = $user_info_result->fetch_assoc();
            $profile_image=$user_info_row['thumbnail_image'];
          }



            //방하나의 객체가될 배열
            $array = array(

              "number" => $row['number'],
              "ranking_tb_id" => $row['season_ranking_tb_id'],
              "user_tb_id" => $user_tb_id,
              "user_tb_nicname" => $row['user_tb_nicname'],
              "season_name" => $row['season_name'],
              "rank_point" => $rank_point,
              "rank" => $row['rank'],
              "profile_image" => $profile_image,
              "tier" => $tier


            );

            //rankingList 배열에 배열저장
            array_push($rankingList,$array);



        }

        $response['success'] = "true";
        $response['rankingList'] = $rankingList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['rankingList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);

?>
