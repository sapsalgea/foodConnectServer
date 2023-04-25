<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$review_id = $_POST['review_id'];
$writing_user_id = $_POST['writing_user_id'];
$comment_content = $_POST['comment_content'];
$comment_class = $_POST['comment_class'];
$sendTargetUserTable_id = $_POST['sendTargetUserTable_id'];
$sendTargetUserNicName = $_POST['sendTargetUserNicName'];



$sql = "SELECT * FROM review_comment LEFT JOIN user_tb ON review_comment.writing_user_id = user_tb.id WHERE review_id = $review_id order by groupNum,comment_order";



        $result = mysqli_query($con, $sql);

        if($result){

        $commentList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){


            //방하나의 객체가될 배열
            $array = array(
              "nick_name" =>$row['nick_name'],
              "profile_image" =>$row['profile_image'],
              "comment_id" => $row['comment_id'],
              "review_id" => $row['review_id'],
              "writing_user_id" => $row['writing_user_id'],
              "comment_content" => $row['comment_content'],
              "comment_class" => $row['comment_class'],
              "groupNum" => $row['groupNum'],
              "comment_order" => $row['comment_order'],
              "comment_Writing_DateTime" => time_ago($row['comment_Writing_DateTime']),
              "sendTargetUserTable_id" => $row['sendTargetUserTable_id'],
              "sendTargetUserNicName" => $row['sendTargetUserNicName'],
              "deleteCheck" => $row['deleteCheck']

            );

            //roomList 배열에 배열저장
            array_push($commentList,$array);



        }


        $response['success'] = "true";
        $response['commentlist'] = $commentList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['commentlist'] = null;

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
