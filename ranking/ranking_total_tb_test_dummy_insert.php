

<?php

$userid = $_POST['userId'];

$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

mysqli_query($con, "set names utf8");


//시즌 구하기

// $today_date = date("Y-m-d");
//
// $today_date_split = explode( '-', $today_date );
//
//
// echo $today_date_split[0];
//
// $year_result = $today_date_split[0] - 2021;
//
// echo $year_result;
//
// if($today_date_split[1]>0 && $today_date_split[1]<7 ){
//   $month_result = 1;
// }else {
//   $month_result = 2;
// }
//
// $now_season = $year_result + $month_result;
//
// echo "현재시즌:".$now_season;



    
    $result = mysqli_query($con,"select * from ranking_test_user_tb");

    if($result){


      //리스폰 배열에 모든방정보 등록
      while($row = $result->fetch_assoc()){


      $id = $row['id'];
      $nic =  $row['nick_name'];
      $randomNumber = rand(0,4000);



      $user_info_result = mysqli_query($con,"INSERT INTO ranking_season_tb (user_tb_id, user_tb_nicname, season_point) VALUES ($id, '$nic', $randomNumber)");

         if($user_info_result){
           echo "트루";
         }else {
           echo "안";
           echo("쿼리오류 발생: " . mysqli_error($con));

         }





      }
    }









mysqli_close($con);

?>
