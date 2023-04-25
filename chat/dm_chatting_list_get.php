<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$MyTableId = $_POST['my_user_tb_id'];
$YouerTableId = 0;



$sql = "SELECT * FROM direct_message_room_tb WHERE room_join_user_tb_id = $MyTableId";



        $result = mysqli_query($con, $sql);

        if($result){

        $chattingList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){

          $roomName = $row['dm_room_name'];

          //DateLine을 제거하는 이유는 데이트라인 삽입시, 텍스트와 데이트라인 생성시간이 같아지는데 이때문에 메시지가아닌 데이트라인이 결과값이 출력된다. 그래서 지워준다.
          $message_log_sql = "SELECT * FROM direct_message_log_tb WHERE room_name = '$roomName' AND  text_or_image_or_dateline not in ('DateLine') ORDER BY dm_log_tb_id DESC LIMIT 1";
          $message_log_result = mysqli_query($con, $message_log_sql);
          $message_log_row = $message_log_result->fetch_assoc();

          //var_dump($message_log_row);
          //
          // echo "<br>";
          //

          $jbexplode = explode( 'and', $roomName );

          //
          // echo "<br>";

          if($MyTableId == $jbexplode[0]){
            $YourTableId = $jbexplode[1];
          }else{
            $YourTableId = $jbexplode[0];
          }

          //echo "상대id :".$YourTableId;
            // echo "<br>";



          $user_info_sql = "SELECT * FROM user_tb WHERE id = $YourTableId";
          $user_info_result = mysqli_query($con, $user_info_sql);
          $user_info_row = $user_info_result->fetch_assoc();
          // var_dump($user_info_row);





          //안읽은 메시지 개수
          $not_read_message_count_sql = "SELECT COUNT(*) FROM direct_message_log_tb WHERE room_name = '$roomName' AND from_user_tb_id = $YourTableId AND message_check ='' ";
          $not_read_message_count_result = mysqli_query($con, $not_read_message_count_sql);
          $not_read_message_count_row = mysqli_fetch_array($not_read_message_count_result);
          //
          // echo "안읽은개수".$not_read_message_count_row[0];

          $now_server_time = date("Y-m-d H:i:s");


          //상대방이 탈퇴했다면 (탈퇴 체크)??

          $is_account_delete = $user_info_row['account_delete'];

          if($is_account_delete ==1){

            //방하나의 객체가될 배열
            $array = array(
              "room_name" =>$roomName,
              "content" =>$message_log_row['content'],
              "text_or_image_or_dateline" =>$message_log_row['text_or_image_or_dateline'],
              "send_time" => $message_log_row['send_time'],
              "your_table_id" => $YourTableId,
              "your_nick_name" => "(탈퇴한 유저)",
              "your_thumbnail_image" => "images/deletedUserProfileImage/deleted_profile_default_image.jpg",
              "not_read_message_count_row"=>$not_read_message_count_row[0],
              "now_server_time" => $now_server_time,
              "is_account_delete" => 1

            );

          }else{

            //방하나의 객체가될 배열
            $array = array(
              "room_name" =>$roomName,
              "content" =>$message_log_row['content'],
              "text_or_image_or_dateline" =>$message_log_row['text_or_image_or_dateline'],
              "send_time" => $message_log_row['send_time'],
              "your_table_id" => $YourTableId,
              "your_nick_name" => $user_info_row['nick_name'],
              "your_thumbnail_image" => $user_info_row['thumbnail_image'],
              "not_read_message_count_row"=>$not_read_message_count_row[0],
              "now_server_time" => $now_server_time,
              "is_account_delete" => 0

            );

          }


          //누군가 1대1 대화창에 들어갔다가 나가면, 방은 생성되지만 메시지가 없어서 불러올때 에러가 생김.
              // 따라서 메시지가 없는 대화창은 뜨지 않게 했다.
            if($array["send_time"] !=null){
              array_push($chattingList,$array);
            }



        }


        $response['success'] = "true";
        $response['chattingList'] = $chattingList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['chattingList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
