<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$room_id = $_POST['room_id'];


        $sql = "SELECT * FROM join_room_tb LEFT JOIN user_tb ON join_room_tb.user_nickname = user_tb.nick_name WHERE room_id = $room_id";


        $result = mysqli_query($con, $sql);

        if($result){

        $userList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){

          $user_nickname = $row['user_nickname'];

          $sql = "SELECT * FROM room_tb WHERE name_host ='$user_nickname' AND id = $room_id";

          $result_count = mysqli_query($con,$sql);
          $count = mysqli_num_rows($result_count);

          if($count == 0){
            $ishost = false;
          } else{
            $ishost = true;
          }

          $is_account_delete = $row['account_delete'];

          if($is_account_delete ==1){


            //탈퇴한 유저일 경우
            $array = array(
              "room_id" =>$row['room_id'],
              "meeting_result" =>$row['meeting_result'],
              "review_result" =>$row['review_result'],
              "user_evaluation" =>$row['user_evaluation'],
              "user_nickname" => "탈퇴한 유저",
              "id" =>$row['id'],
              "profile_image" => "images/deletedUserProfileImage/deleted_profile_default_image.jpg",
              "thumbnail_image" => "images/deletedUserProfileImage/deleted_profile_default_image.jpg",
              "ishost" => $ishost,
              "is_account_delete" => 1

            );

          }else {

            //탈퇴한 유저가 아닐 경우
            $array = array(
              "room_id" =>$row['room_id'],
              "meeting_result" =>$row['meeting_result'],
              "review_result" =>$row['review_result'],
              "user_evaluation" =>$row['user_evaluation'],
              "user_nickname" => $user_nickname,
              "id" =>$row['id'],
              "profile_image" => $row['profile_image'],
              "thumbnail_image" => $row['thumbnail_image'],
              "ishost" => $ishost,
              "is_account_delete" => 0

            );

          }



            //roomList 배열에 배열저장
            array_push($userList,$array);



        }


        $response['success'] = "true";
        $response['userList'] = $userList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['userList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
