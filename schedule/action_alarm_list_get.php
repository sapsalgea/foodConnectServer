<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$user_tb_id = $_POST['user_tb_id'];



$sql = "SELECT * FROM action_alarm_tb WHERE receiver_user_tb_id = $user_tb_id ORDER BY action_alarm_tb_id DESC";



        $result = mysqli_query($con, $sql);

        if($result){

        $actionAlarmList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){



            //방하나의 객체가될 배열
            $array = array(
              "action_alarm_tb_id" =>$row['action_alarm_tb_id'],
              "receiver_user_tb_id" =>$row['receiver_user_tb_id'],
              "action_type" => $row['action_type'],
              "sender_user_tb_id" => $row['sender_user_tb_id'],
              "sender_user_tb_nicname" => $row['sender_user_tb_nicname'],
              "which_text_choose" => $row['which_text_choose'],
              "sender_comment_content" => $row['sender_comment_content'],
              "review_id" => $row['review_id'],
              "groupNum" => $row['groupNum'],
              "comment_writing_user_id" => $row['comment_writing_user_id'],
              "comment_writing_user_nicname" => $row['comment_writing_user_nicname'],
              "reviewWritingUserId" => $row['reviewWritingUserId'],
              "action_datetime" => $row['action_datetime'],
              "time_ago" => time_ago($row['action_datetime'])

            );

            //roomList 배열에 배열저장
            array_push($actionAlarmList,$array);



        }


        $response['success'] = "true";
        $response['ActionAlarmList'] = $actionAlarmList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['ActionAlarmList'] = null;

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
