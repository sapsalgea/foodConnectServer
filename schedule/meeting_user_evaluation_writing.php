<?php
header('Content-type: application/json;');



$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con,'SET NAMES utf8');

$json = $_POST['meeting_user_evaluation_Json'];
$my_user_tb_id = $_POST['my_user_tb_id'];
$my_user_nicname = $_POST['my_user_nicname'];

$room_id = 0;


$writing_time = date("Y-m-d H:i:s");

$jsonArray=json_decode(stripcslashes(trim($json,'"')));

$result_count = 0;

for($x = 0; $x < count($jsonArray); $x++) {

    $room_id = $jsonArray[$x]->room_id;
    $to_user_tb_id = $jsonArray[$x]->id;
    $evaluation_str = $jsonArray[$x]->user_evaluation_what_did_you_say;



    //평가기록 테이블에 $to_user_tb_id의 컬럼이 있는지 확인
    $record_sql = "SELECT COUNT(*) FROM user_evaluation_record WHERE user_tb_id = $to_user_tb_id";
    $record_result = mysqli_query($con, $record_sql);
    $row = mysqli_fetch_array($record_result);


    //데이터가 없으므로, db에 기록해준다.
    if($row[0] == 0){

      $record_insert_sql = "INSERT INTO user_evaluation_record (user_tb_id) VALUES ($to_user_tb_id)";
      $record_insert_result = mysqli_query($con,$record_insert_sql);

      if($record_insert_result){

      }else{

      }
    }


    if($evaluation_str == "유쾌함"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET delightful_type = delightful_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "고독한미식가"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET gourmet_type = gourmet_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "재미있음"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET funny_type = funny_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "시끄러움"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET noisy_type = noisy_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "무뚝뚝"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET curt_type = curt_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "맛잘알"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET food_smart_type = food_smart_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "친화력갑"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET sociability_type = sociability_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "미소지기"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET smile_type = smile_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "부담스러움"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET uncomfortable_type = uncomfortable_type+1 WHERE user_tb_id = $to_user_tb_id";
    }else if($evaluation_str == "노쇼"){
        $evaluation_number_update_sql = "UPDATE user_evaluation_record SET no_show = no_show+1 WHERE user_tb_id = $to_user_tb_id";
    }


    $evaluation_number_update_result = mysqli_query($con,$evaluation_number_update_sql);



    //어떤사람이 어떤 평가를 했는지 평가기록을 저장한다.
    $sql = "Insert into meeting_end_user_evaluation (room_id,from_user_tb_id,to_user_tb_id,evaluation_str,evaluation_time)values($room_id,$my_user_tb_id,$to_user_tb_id,'$evaluation_str', '$writing_time')";
    $result = mysqli_query($con, $sql);

    if($result){
      $result_count = $result_count+1;
    }

    if($evaluation_str == "노쇼"){



      //이 유저가 현재 노쇼체크를 받았는지 확인한다.
      $noshow_user_state_sql = "SELECT * FROM join_room_tb WHERE room_id = $room_id AND user_index = $to_user_tb_id";
      $noshow_user_state_row = mysqli_fetch_assoc(mysqli_query($con,$noshow_user_state_sql));

      //만약 아직 노쇼를 받지 않았다면..
      if($noshow_user_state_row['no_show'] == 0){


        //평가 받은 사람이 이번 모임에서 다른유저들에게 노쇼를 몇개 받았는지 구한다.

        $noshow_count_query = "SELECT COUNT(*) FROM meeting_end_user_evaluation WHERE room_id = $room_id AND to_user_tb_id = $to_user_tb_id AND evaluation_str ='노쇼'";
        $noshow_count_data = mysqli_query($con, $noshow_count_query);
        $noshow_count_count_row = mysqli_fetch_array($noshow_count_data);
        $noshow_count = $noshow_count_count_row[0];



        //모임 참가자 인원수
        $join_user_count_query = "SELECT COUNT(*) FROM join_room_tb WHERE room_id = $room_id";
        $join_user_count_data = mysqli_query($con, $join_user_count_query);
        $join_user_count_count_row = mysqli_fetch_array($join_user_count_data);
        $join_user_count = $join_user_count_count_row[0];



        if($noshow_count>=($join_user_count/2)){
          $no_show_count_update_sql = "UPDATE join_room_tb SET no_show = 1 WHERE room_id = $room_id AND user_index = $to_user_tb_id";
          $no_show_count_update_result = mysqli_query($con,$no_show_count_update_sql);


          $meeting_result_update_sql = "UPDATE join_room_tb SET meeting_result = 2 WHERE room_id = $room_id AND user_index = $to_user_tb_id";
          $meeting_result = mysqli_query($con,$meeting_result_update_sql);



          //노쇼했으므로 랭킹포인트 차감

          $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-10 WHERE user_tb_id= $to_user_tb_id";
          mysqli_query($con,$point_plus_update_sql);



          //랭킹포인트 적립 히스토리에 기록하기.
          $record_datetime = date("Y-m-d H:i:s");


          $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
          point_get_user_tb_id,
          how_to_get_point,
          how_many_point,
          record_datetime)
          VALUES (
            $to_user_tb_id,
            'no_show',
            '-10',
            '$record_datetime'
            )";

          $ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);



          //기능2 -여기부터  만약 노쇼한 유저가 거짓으로 평가를 완료했다면, 평가한 기록을 삭제시켜준다.

          $is_noshow_user_evaluation_sql = "SELECT * FROM join_room_tb WHERE room_id = $room_id AND user_index = $to_user_tb_id";
          $is_noshow_user_evaluation_row = mysqli_fetch_assoc(mysqli_query($con,$is_noshow_user_evaluation_sql));




          if($is_noshow_user_evaluation_row['user_evaluation'] == 1){



            $noshow_user_eval_find_sql = "SELECT * FROM meeting_end_user_evaluation WHERE room_id = $room_id AND from_user_tb_id = $to_user_tb_id";



                    $noshow_user_eval_find_result = mysqli_query($con, $noshow_user_eval_find_sql);

                    if($noshow_user_eval_find_result){

                    while($row = $noshow_user_eval_find_result->fetch_assoc()){



                        $find_to_user_tb_id = $row['to_user_tb_id'];
                        $find_evaluation_str = $row['evaluation_str'];


                        if($find_evaluation_str == "유쾌함"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET delightful_type = delightful_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "고독한미식가"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET gourmet_type = gourmet_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "재미있음"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET funny_type = funny_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "시끄러움"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET noisy_type = noisy_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "무뚝뚝"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET curt_type = curt_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "맛잘알"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET food_smart_type = food_smart_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "친화력갑"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET sociability_type = sociability_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "미소지기"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET smile_type = smile_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "부담스러움"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET uncomfortable_type = uncomfortable_type-1 WHERE user_tb_id = $find_to_user_tb_id";
                        }else if($find_evaluation_str == "노쇼"){
                            $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET no_show = no_show-s1 WHERE user_tb_id = $find_to_user_tb_id";
                        }

                       $find_evaluation_number_update_result = mysqli_query($con,$find_evaluation_number_update_sql);


                    }
                  }

                  if($find_evaluation_number_update_result){
                    $eval_record_del_sql = "DELETE FROM meeting_end_user_evaluation WHERE room_id = $room_id AND from_user_tb_id = $to_user_tb_id";
                    mysqli_query($con, $eval_record_del_sql);
                  }

                  //모임원 평가로 획득한 랭킹포인트 회수
                  $point_minus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-10 WHERE user_tb_id= $to_user_tb_id";
                  mysqli_query($con,$point_minus_update_sql);


                  //랭킹포인트 적립 히스토리에 기록하기.
                  $record_datetime = date("Y-m-d H:i:s");

                  $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
                  point_get_user_tb_id,
                  how_to_get_point,
                  how_many_point,
                  record_datetime)
                  VALUES (
                    $to_user_tb_id,
                    'no_show_evaluation_del',
                    '-10',
                    '$record_datetime'
                    )";

                  $ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);








          }//기능2 - 여기까지



          //기능3 -노쇼한 사람이 리뷰를 작성했다면?
          $no_show_user_review_count_query = "SELECT COUNT(*) FROM review_tb WHERE room_tb_id = $room_id AND writer_user_tb_id = $to_user_tb_id";
          $no_show_user_review_data = mysqli_query($con, $no_show_user_review_count_query);
          $no_show_user_review_row = mysqli_fetch_array($no_show_user_review_data);
          $no_show_user_review_count = $no_show_user_review_row[0];

          if($no_show_user_review_count == 1){
            //노쇼한 유저가 리뷰를 적었다면..
            //리뷰를 삭제한다.

            $review_deleted_update_sql = "UPDATE review_tb SET review_deleted = 1 WHERE room_tb_id = $room_id AND writer_user_tb_id = $to_user_tb_id";
            mysqli_query($con,$review_deleted_update_sql);



            //좋아요로 얻은 추가점수도 빼준다.
            $extra_point_get_sql = "SELECT * FROM review_tb WHERE room_tb_id = $room_id AND writer_user_tb_id = $to_user_tb_id";
            $extra_point_get_row = mysqli_fetch_assoc(mysqli_query($con,$extra_point_get_sql));
            $ranking_extra_point = $extra_point_get_row['ranking_extra_point'];
            $minus_point = $ranking_extra_point + 10;


            //리뷰작성으로 획득한 랭킹포인트 차감
            $point_minus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-$minus_point WHERE user_tb_id= $to_user_tb_id";
            mysqli_query($con,$point_minus_update_sql);



            //랭킹포인트 적립 히스토리에 기록하기.
            $record_datetime = date("Y-m-d H:i:s");


            $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
            point_get_user_tb_id,
            how_to_get_point,
            how_many_point,
            record_datetime)
            VALUES (
              $to_user_tb_id,
              'no_show_review_del',
              -$minus_point,
              '$record_datetime'
              )";

            mysqli_query($con,$ranking_point_record_insert_sql);

          }






        }



      }









    }






}




if($result_count == count($jsonArray)){


    //유저평가를 완료했다고 기록한다.
    $evaluation_number_update_sql = "UPDATE join_room_tb SET user_evaluation = 1 WHERE user_nickname = $my_user_nicname AND room_id = $room_id";
    $result = mysqli_query($con, $evaluation_number_update_sql);



    //랭킹포인트 획득
    $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point+10 WHERE user_tb_id= $my_user_tb_id";
    mysqli_query($con,$point_plus_update_sql);



    //랭킹포인트 적립 히스토리에 기록하기.
    $record_datetime = date("Y-m-d H:i:s");


    $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
    point_get_user_tb_id,
    how_to_get_point,
    how_many_point,
    record_datetime)
    VALUES (
      $my_user_tb_id,
      'evaluation_complete',
      '10',
      '$record_datetime'
      )";

    $ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);




    $ranking_get_sql = "SELECT * FROM ranking_season_tb WHERE user_tb_id = $my_user_tb_id";
    $ranking_get_row = mysqli_fetch_assoc(mysqli_query($con,$ranking_get_sql));
    $season_point = $ranking_get_row['season_point'];

    $response['success'] = true;
    $response['get_season_point'] = 10;
    $response['now_season_total_rangking_point'] = $season_point;



    echo json_encode($response);

}else{

  $response['success'] = false;
  $response['get_season_point'] = 0;
  $response['now_season_total_rangking_point'] = 0;


  echo json_encode($response);
}






mysqli_close($con);





?>
