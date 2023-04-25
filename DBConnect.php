<?php 
//DB연결
$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
    mysqli_query($con, "set names utf8");
    date_default_timezone_set("Asia/Seoul");
    ?>