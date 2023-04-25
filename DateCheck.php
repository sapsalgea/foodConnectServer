<?php 
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");
$aa=strtotime(date('Y-m-d',strtotime("+1 day"))."00:00:00");

$now_time = strtotime("now");

date("Y-m-d H:i:s",$now_time);

date("Y-m-d H:i:s",$aa);
$calc_date = $aa - $now_time;

$hour = substr("0".floor($calc_date / 3600),-2);
$minute =  substr("0".(floor($calc_date / 60) - ($hour*60)),-2) ;
$second = substr("0".($calc_date % 60),-2);


$hour.":".$minute.":".$second;

echo $calc_date;


