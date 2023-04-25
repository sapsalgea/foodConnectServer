<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");

// $review_id = $_POST['review_id'];
// $writing_user_id = $_POST['writing_user_id'];
// $comment_content = $_POST['comment_content'];
// $comment_class = $_POST['comment_class'];
// $sendTargetUserTable_id = $_POST['sendTargetUserTable_id'];
// $sendTargetUserNicName = $_POST['sendTargetUserNicName'];
//
//
//
// $query = "SELECT COUNT(groupNum) FROM review_comment WHERE review_id =343";
// $data = mysqli_query($con, $query);
// $row = mysqli_fetch_array($data);
// $total_rows = $row[0];
//
// echo $total_rows;

// $query = "SELECT comment_count FROM review_tb WHERE review_id = 355";
// $data = mysqli_query($con, $query);
// $row = $data->fetch_assoc();
// echo $row['comment_count'];


// $noshow_count_query = "SELECT COUNT(*) FROM meeting_end_user_evaluation WHERE room_id = 222 AND to_user_tb_id = 86 AND evaluation_str ='노쇼'";
// $noshow_count_data = mysqli_query($con, $noshow_count_query);
// $noshow_count_count_row = mysqli_fetch_array($noshow_count_data);
// $noshow_count = $noshow_count_count_row[0];
//
// echo $noshow_count;
//
// //모임 참가자 인원수
// $join_user_count_query = "SELECT COUNT(*) FROM join_room_tb WHERE room_id = 222";
// $join_user_count_data = mysqli_query($con, $join_user_count_query);
// $join_user_count_count_row = mysqli_fetch_array($join_user_count_data);
// $join_user_count = $join_user_count_count_row[0];
//
// echo $join_user_count/2;
// //
// if($noshow_count>($join_user_count/2)){
//   echo "크다";
//   // $no_show_count_update_sql = "UPDATE join_room_tb SET no_show = 1 WHERE room_id = 222 AND user_index =86";
//   // $no_show_count_update_result = mysqli_query($con,$no_show_count_update_sql);
// }else {
//   echo "작다";
// }
//



// 아래는 노쇼체크
// $room_id = 224;
// $to_user_tb_id = 2112;
//
// $noshow_count_query = "SELECT COUNT(*) FROM meeting_end_user_evaluation WHERE room_id = $room_id AND to_user_tb_id = $to_user_tb_id AND evaluation_str ='노쇼'";
// $noshow_count_data = mysqli_query($con, $noshow_count_query);
// $noshow_count_count_row = mysqli_fetch_array($noshow_count_data);
// $noshow_count = $noshow_count_count_row[0];
//
//
//
// //모임 참가자 인원수
// $join_user_count_query = "SELECT COUNT(*) FROM join_room_tb WHERE room_id = $room_id";
// $join_user_count_data = mysqli_query($con, $join_user_count_query);
// $join_user_count_count_row = mysqli_fetch_array($join_user_count_data);
// $join_user_count = $join_user_count_count_row[0];
//
//
// if($noshow_count>($join_user_count/2)){
//   $no_show_count_update_sql = "UPDATE join_room_tb SET no_show = 1 WHERE room_id = $room_id AND user_index = $to_user_tb_id";
//   $no_show_count_update_result = mysqli_query($con,$no_show_count_update_sql);
//
//
//   $meeting_result_update_sql = "UPDATE join_room_tb SET meeting_result = 2 WHERE room_id = $room_id AND user_index = $to_user_tb_id";
//   $meeting_result = mysqli_query($con,$meeting_result_update_sql);
//
//
//
//   //노쇼했으므로 랭킹포인트 차감
//
//   $point_plus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-10 WHERE user_tb_id= $to_user_tb_id";
//   mysqli_query($con,$point_plus_update_sql);
//
//
//
//   //랭킹포인트 적립 히스토리에 기록하기.
//   $record_datetime = date("Y-m-d H:i:s");
//
//
//   $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
//   point_get_user_tb_id,
//   how_to_get_point,
//   how_many_point,
//   record_datetime)
//   VALUES (
//     $to_user_tb_id,
//     'no_show',
//     '-10',
//     '$record_datetime'
//     )";
//
//   $ranking_point_record_insert_result = mysqli_query($con,$ranking_point_record_insert_sql);


//}
//
// $user_index = 2112;
//
//
//
//    $is_noshow_user_evaluation_sql = "SELECT * FROM join_room_tb WHERE room_id = 224 AND user_index = $user_index";
//
//    $is_noshow_user_evaluation_row = mysqli_fetch_assoc(mysqli_query($con,$is_noshow_user_evaluation_sql));
//
//
//    echo $is_noshow_user_evaluation_row['user_evaluation'];
//
//    if($is_noshow_user_evaluation_row['user_evaluation'] == 1){
//      echo "같다";
//
//      //만약 노쇼한 사람 모임원 평가를 했다면, 그 기록을 지운다.
//      $noshow_user_eval_find_sql = "SELECT * FROM meeting_end_user_evaluation WHERE room_id = 224 AND from_user_tb_id = $user_index";
//
//
//
//              $noshow_user_eval_find_result = mysqli_query($con, $noshow_user_eval_find_sql);
//
//              if($noshow_user_eval_find_result){
//
//              while($row = $noshow_user_eval_find_result->fetch_assoc()){
//
//
//
//                  $find_to_user_tb_id = $row['to_user_tb_id'];
//                  $find_evaluation_str = $row['evaluation_str'];
//
//
//                  if($find_evaluation_str == "유쾌함"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET delightful_type = delightful_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "고독한미식가"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET gourmet_type = gourmet_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "재미있음"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET funny_type = funny_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "시끄러움"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET noisy_type = noisy_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "무뚝뚝"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET curt_type = curt_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "맛잘알"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET food_smart_type = food_smart_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "친화력갑"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET sociability_type = sociability_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "미소지기"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET smile_type = smile_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "부담스러움"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET uncomfortable_type = uncomfortable_type-1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }else if($find_evaluation_str == "노쇼"){
//                      $find_evaluation_number_update_sql = "UPDATE user_evaluation_record SET no_show = no_show-s1 WHERE user_tb_id = $find_to_user_tb_id";
//                  }
//
//                 $find_evaluation_number_update_result = mysqli_query($con,$find_evaluation_number_update_sql);
//
//
//              }
//            }
//
//            if($find_evaluation_number_update_result){
//              $eval_record_del_sql = "DELETE FROM meeting_end_user_evaluation WHERE room_id = 224 AND from_user_tb_id = $user_index";
//              mysqli_query($con, $eval_record_del_sql);
//            }
//
//
//
//
//
//
//
//
//    }


   // $noshow_user_state_sql = "SELECT * FROM review_tb WHERE room_tb_id = 224 AND writer_user_tb_id = 2112";
   // $noshow_user_state_row = mysqli_fetch_assoc(mysqli_query($con,$noshow_user_state_sql));
   //
   // if($noshow_user_state_row['no_show'] == 0){
   //   echo "string";
   // }

   // //댓글 개수
   // $no_show_user_review_count_query = "SELECT COUNT(*) FROM review_tb WHERE room_tb_id = 224 AND writer_user_tb_id = 2112";
   // $no_show_user_review_data = mysqli_query($con, $no_show_user_review_count_query);
   // $no_show_user_review_row = mysqli_fetch_array($no_show_user_review_data);
   // $no_show_user_review_count = $no_show_user_review_row[0];
   //
   // if($no_show_user_review_count == 1){
   //   //노쇼한 유저가 리뷰를 적었다면..
   //   //리뷰를 삭제한다.
   //
   //   $review_deleted_update_sql = "UPDATE review_tb SET review_deleted = 1 WHERE room_tb_id = 224 AND writer_user_tb_id = 2112";
   //   mysqli_query($con,$review_deleted_update_sql);
   //
   //
   //   //리뷰작성으로 획득한 랭킹포인트 차감
   //   $point_minus_update_sql = "UPDATE ranking_season_tb SET season_point=season_point-10 WHERE user_tb_id= 2112";
   //   mysqli_query($con,$point_minus_update_sql);
   //
   //
   //
   //   //랭킹포인트 적립 히스토리에 기록하기.
   //   $record_datetime = date("Y-m-d H:i:s");
   //
   //
   //   $ranking_point_record_insert_sql = "INSERT INTO ranking_point_record_tb (
   //   point_get_user_tb_id,
   //   how_to_get_point,
   //   how_many_point,
   //   record_datetime)
   //   VALUES (
   //     $to_user_tb_id,
   //     'no_show_review_del',
   //     '-10',
   //     '$record_datetime'
   //     )";
   //
   //   mysqli_query($con,$ranking_point_record_insert_sql);
   //
   // }



   $ranking_get_sql = "SELECT * FROM ranking_season_tb WHERE user_tb_id = 84";
   $ranking_get_row = mysqli_fetch_assoc(mysqli_query($con,$ranking_get_sql));
   echo $ranking_get_row['season_point'];


   // if($ranking_get_row['ranking_extra_point'] ==11){
   //    10 - $minus_point;
   // }





echo "작다";


mysqli_close($con);





?>
