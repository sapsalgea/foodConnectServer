<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");


$user_tb_id = $_POST['user_tb_id'];





        $sql = "SELECT * FROM user_evaluation_record WHERE user_tb_id = $user_tb_id";


        $result = mysqli_query($con, $sql);

        if($result){

        $evaluationList = array();


        //리스폰 배열에 모든방정보 등록
        while($row = $result->fetch_assoc()){


            //방하나의 객체가될 배열
            $array = array(
              "record_id" =>$row['record_id'],
              "user_tb_id" =>$row['user_tb_id'],
              "delightful_type" =>$row['delightful_type'],
              "gourmet_type" =>$row['gourmet_type'],
              "funny_type" =>$row['funny_type'],
              "noisy_type" =>$row['noisy_type'],
              "curt_type" =>$row['curt_type'],
              "food_smart_type" => $row['food_smart_type'],
              "sociability_type" =>$row['sociability_type'],
              "smile_type" => $row['smile_type'],
              "uncomfortable_type" => $row['uncomfortable_type']
            );

            //roomList 배열에 배열저장
            array_push($evaluationList,$array);



        }


        $response['success'] = "true";
        $response['evaluationList'] = $evaluationList;

        echo json_encode($response);
      }else{
                $response['success'] = "false";
                $response['evaluationList'] = null;

        echo json_encode($response);
      }





mysqli_close($con);
?>
