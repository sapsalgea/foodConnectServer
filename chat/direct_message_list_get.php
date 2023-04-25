<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

$roomName = $_POST['roomName'];
$dm_log_tb_id = $_POST['dm_log_tb_id'];

if($dm_log_tb_id == -1){
    $sql = "SELECT * FROM (SELECT * FROM direct_message_log_tb WHERE room_name = $roomName ORDER BY dm_log_tb_id DESC LIMIT 20) q ORDER BY q.dm_log_tb_id ASC";
}else{
  $sql = "SELECT * FROM (SELECT * FROM direct_message_log_tb WHERE room_name = $roomName AND dm_log_tb_id < $dm_log_tb_id ORDER BY dm_log_tb_id DESC LIMIT 20) q ORDER BY q.dm_log_tb_id DESC";
}








        $result = mysqli_query($con, $sql);

        if($result){

        $chattingList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){



            //방하나의 객체가될 배열
            $array = array(
              "dm_log_tb_id" =>$row['dm_log_tb_id'],
              "room_name" =>$row['room_name'],
              "from_user_tb_id" => $row['from_user_tb_id'],
              "to_user_tb_id" => $row['to_user_tb_id'],
              "content" => $row['content'],
              "text_or_image_or_dateline" => $row['text_or_image_or_dateline'],
              "send_time" => $row['send_time'],
              "message_check" => $row['message_check']
            );

            //roomList 배열에 배열저장
            array_push($chattingList,$array);



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
