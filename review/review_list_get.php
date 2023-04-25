<?php

$userid = $_POST['userId'];

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


// $sql = "SELECT * FROM review_tb LEFT JOIN user_tb ON review_tb.writer_user_tb_id = user_tb.id WHERE review_deleted = 0 AND account_delete = 0 ORDER BY review_id DESC";
$sql = "SELECT * FROM review_tb LEFT JOIN user_tb ON review_tb.writer_user_tb_id = user_tb.id WHERE review_deleted = 0 AND account_delete = 0 ORDER BY review_id DESC";







        $result = mysqli_query($con, $sql);

        if($result){

        $reviewList = array();

        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){

          $review_id_text = $row['review_id'];

          $sql = "SELECT * FROM review_like_clicked_user WHERE review_tb_id = '$review_id_text' AND user_tb_id = $userid";

          $result_count = mysqli_query($con,$sql);
          $count = mysqli_num_rows($result_count);

          if($count == 0){
            $heart_making = false;
          } else{
            $heart_making = true;
          }


          //댓글 개수
          $query = "SELECT COUNT(*) FROM review_comment WHERE review_id = '$review_id_text' AND deleteCheck = 0";
          $data = mysqli_query($con, $query);
          $comment_count_row = mysqli_fetch_array($data);
          $comment_count = $comment_count_row[0];

            //방하나의 객체가될 배열
            $array = array(

              "review_id" => $review_id_text,
              "room_tb_id" => $row['room_tb_id'],
              "writer_user_tb_id" => $row['writer_user_tb_id'],
              "writer_uid" => $row['writer_uid'],
              "writer_nicname" => $row['nick_name'],
              "restaurant_address" => $row['restaurant_address'],
              "restaurant_name" => $row['restaurant_name'],
              "reporting_date" => time_ago($row['reporting_date']),
              "appointment_day" => $row['appointment_day'],
              "appointment_time" => $row['appointment_time'],
              "review_description" => $row['review_description'],
              "rating_star_taste" => $row['rating_star_taste'],
              "rating_star_service" => $row['rating_star_service'],
              "rating_star_clean" => $row['rating_star_clean'],
              "rating_star_interior" => $row['rating_star_interior'],
              "review_picture_0" => $row['review_picture_0'],
              "review_picture_1" => $row['review_picture_1'],
              "review_picture_2" => $row['review_picture_2'],
              "like_count" => $row['like_count'],
              "heart_making" => $heart_making,
              "comment_count" => $comment_count,
              "nick_name" => $row['nick_name'],
              "profile_image" => $row['profile_image']



                // "title" => $row['title'],
                // "description" => $row['description'],
                // "restaurant_address" => $row['restaurant_address']

            );

            //roomList 배열에 배열저장
            array_push($reviewList,$array);



        }

        $response['success'] = "true";
        $response['roomList'] = $reviewList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['roomList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);




function time_ago($timestamp)
 {
      $time_ago = strtotime($timestamp);
      $current_time = time();
      $time_difference = $current_time - $time_ago;
      $seconds = $time_difference;
      $minutes      = round($seconds / 60 );           // value 60 is seconds
      $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec
      $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;
      $weeks          = round($seconds / 604800);          // 7*24*60*60;
      $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60
      $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60
      if($seconds <= 60)
      {
        return "방금전";
      }
      else if($minutes <=60)
      {
       return $minutes."분전";
     }
      else if($hours <=24)
      {
        return $hours."시간전";
      }
      else if($days <= 7)
      {
         if($days==1)
               {
           return "어제";
         }
               else
               {
           return $days."일전";
         }
       }
      else if($weeks <= 4.3) //4.3 == 52/12
      {
     if($weeks==1)
           {
       return "일주일전";
     }
           else
           {
       return $weeks."주전";
     }
   }
       else if($months <=12)
      {
     if($months==1)
           {
       return "한달전";
     }
           else
           {
       return $months."달전";
     }
   }
      else
      {
        return date('Y.m.d', strtotime($timestamp));
      }
 }
?>
