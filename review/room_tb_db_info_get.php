<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$room_id = $_POST['room_id'];



        $sql = "SELECT * FROM room_tb WHERE id = $room_id";



        $result = mysqli_query($con, $sql);

        if($result){

        $roomInfoList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){


            //방하나의 객체가될 배열
            $array = array(
              "id" =>$row['id'],
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
            array_push($roomInfoList,$array);



        }


        $response['success'] = "true";
        $response['roomInfoList'] = $roomInfoList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['roomInfoList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
