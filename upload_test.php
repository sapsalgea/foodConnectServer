<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "test");

if($con){
    echo "connect : 성공<br>";
}
else{
    echo "disconnect : 실패<br>";
}

$username = $_POST['mode'];

mysqli_query($con,"INSERT INTO testTable (name) VALUES ('$username')");


mysqli_close($con);

?>
