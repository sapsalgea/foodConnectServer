<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$user_tb_id = $_POST['user_tb_id'];


$sql = "SELECT * FROM before_season_tier_tb WHERE user_tb_id = $user_tb_id ORDER BY season_name DESC";

$result = mysqli_query($con, $sql);

        if($result){

        $rankingList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){



          $user_tier = $row['user_tier'];

          $tier_image = "ranking/tier_badge_image/stone_1.png";

          if($user_tier=="챌린저"){

            $tier_image = "ranking/tier_badge_image/challenger_1.png";
          }
          else if($user_tier=="마스터"){

            $tier_image = "ranking/tier_badge_image/master_1.png";
          }

          else if($user_tier=="다이아몬드"){

            $tier_image = "ranking/tier_badge_image/diamond_1.png";
          }

          else if($user_tier=="스톤"){

            $tier_image = "ranking/tier_badge_image/stone_1.png";
          } else if($user_tier=="브론즈"){

            $tier_image = "ranking/tier_badge_image/bronze_1.png";
          } else if($user_tier=="실버"){

            $tier_image = "ranking/tier_badge_image/silver_1.png";
          } else if($user_tier=="골드"){

            $tier_image = "ranking/tier_badge_image/gold_1.png";
          } else if($user_tier=="플래티넘"){

            $tier_image = "ranking/tier_badge_image/platinum_1.png";
          }


            //방하나의 객체가될 배열
            $array = array(
              "before_season_tier_tb_id" =>$row['before_season_tier_tb_id'],
              "season_name" =>$row['season_name'],
              "user_tb_id" => $row['user_tb_id'],
              "user_nicname" => $row['user_nicname'],
              "season_rank" => $row['season_rank'],
              "season_count_number" => $row['season_count_number'],
              "ranking_point" => $row['ranking_point'],
              "user_tier" => $row['user_tier'],
              "tier_image" => $tier_image
            );

            //roomList 배열에 배열저장
            array_push($rankingList,$array);



        }


        $response['success'] = "true";
        $response['RankingLatestThreeList'] = $rankingList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['rankingList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
