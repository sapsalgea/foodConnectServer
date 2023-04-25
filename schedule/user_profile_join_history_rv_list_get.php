<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$user_tb_nicname = $_POST['user_tb_nicname'];


        $sql = "SELECT * FROM join_room_tb LEFT JOIN room_tb ON join_room_tb.room_id = room_tb.id WHERE user_nickname = $user_tb_nicname AND meeting_result = 1 ORDER BY appointment_day DESC, appointment_time DESC";


        $result = mysqli_query($con, $sql);

        if($result){

        $scheduleList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){


            //방하나의 객체가될 배열
            $array = array(
              "room_id" =>$row['room_id'],
              "meeting_result" =>$row['meeting_result'],
              "review_result" =>$row['review_result'],
              "user_evaluation" =>$row['user_evaluation'],
              "room_title" =>$row['room_title'],
              "room_introduce" =>$row['room_introduce'],
              "now_member_count" =>$row['now_member_count'],
              "member_count" => $row['member_count'],
              "join_users" =>$row['join_users'],
              "restaurant_address" => $row['restaurant_address'],
              "restaurant_roadaddress" => $row['restaurant_roadaddress'],
              "restaurant_placename" => $row['restaurant_placename'],
              "restaurant_name"=>$row['restaurant_name'],
              "gender_selection"=>$row['gender_selection'],
              "reporting_date" => $row['reporting_date'],
              "minimum_age" => $row['minimum_age'],
              "maximum_age" => $row['maximum_age'],
              "appointment_day" => $row['appointment_day'],
              "appointment_time" => $row['appointment_time'],
              "name_host" => $row['name_host'],
              "room_status" => $row['room_status'],
              "search_keyword" => $row['search_keyword'],
              "map_x" => $row['map_x'],
              "map_y" => $row['map_y']


            );

            //roomList 배열에 배열저장
            array_push($scheduleList,$array);



        }


        //호스트 참가 횟수
        $query = "SELECT COUNT(*) FROM join_room_tb LEFT JOIN room_tb ON join_room_tb.room_id = room_tb.id WHERE user_nickname = $user_tb_nicname AND meeting_result = 1 AND name_host=$user_tb_nicname";
        $data = mysqli_query($con, $query);
        $row = mysqli_fetch_array($data);
        $host_count = $row[0];


        //게스트  참가 횟수
        $query = "SELECT COUNT(*) FROM join_room_tb LEFT JOIN room_tb ON join_room_tb.room_id = room_tb.id WHERE user_nickname = $user_tb_nicname AND meeting_result = 1 AND name_host!=$user_tb_nicname";
        $data = mysqli_query($con, $query);
        $row = mysqli_fetch_array($data);
        $guest_count = $row[0];


        $response['success'] = "true";
        $response['host_count'] = $host_count;
        $response['guest_count'] = $guest_count;
        $response['scheduleList'] = $scheduleList;

        echo json_encode($response);
      }else{
                $response['host_count'] = 0;
                $response['guest_count'] = 0;
                $response['success'] = "false";
                $response['scheduleList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
