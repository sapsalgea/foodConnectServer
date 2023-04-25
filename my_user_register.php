<?php


$user_id = $_POST['user_id'];
$social_login_type = $_POST['social_login_type'];
$nick_name = $_POST['nick_name'];
$birth_year = $_POST['birth_year'];
$user_gender = $_POST['user_gender'];
$phone_number = $_POST['phone_number'];

// 파일 받기
$profile_File =$_FILES['uploaded_file']['name'];


// 저장할 경로
$file_name =$_SERVER['DOCUMENT_ROOT']. '/images/profile_image/';
$tempData = $_FILES['uploaded_file']['tmp_name'];
$name = basename($_FILES["uploaded_file"]["name"]);



// // 임시폴더에서  ->  경로 이동 .파일이름

if(isset($profile_File)){
 if(move_uploaded_file($tempData, $file_name.$name)){



    $data_path = "images/profile_image";
    $thumb_path = "images/profile_image_thumbnail";

    // @mkdir($thumb_path, 0707);
    // @chmod($thumb_path, 0707);

    $org_file = $profile_File;
    $original_path = $data_path."/".$org_file;
    $save_path = $thumb_path."/".$org_file;

    if(!is_file($save_path) && is_file($original_path)){

      list($width, $height, $type, $attr) = getimagesize($original_path);

      $org_file = explode(".",$org_file);
      $org_type = $org_file[(count($org_file) -1)];
      $new_img=imagecreatetruecolor(100,100); // 가로 300 픽셀, 세로 200 픽셀

      if(strtolower($org_type) == "jpg" || strtolower($org_type) == "jpeg") $origin_img = imagecreatefromjpeg($original_path);
      else if(strtolower($org_type) == "gif") $origin_img = imagecreatefromgif($original_path);
      else if(strtolower($org_type) == "bmp") $origin_img = imagecreatefromwbmp($original_path);
      else if(strtolower($org_type) == "png") $origin_img = imagecreatefromjpeg($original_path);

      //imagecopyresampled($new_img, $origin_img, 0, 0, $offset_x, $offset_y, $width, $height, $crop_width, $crop_height);
      imagecopyresampled($new_img, $origin_img, 0, 0, 0, 0, 100, 100, $width, $height);

      if(strtolower($org_type) == "jpg" || strtolower($org_type) == "jpeg") imagejpeg($new_img, $save_path);
      else if(strtolower($org_type) == "gif") imagegif($new_img, $save_path);
      else if(strtolower($org_type) == "bmp") imagewbmp($new_img, $save_path);
      else if(strtolower($org_type) == "png") imagejpeg($new_img, $save_path);

    }


    $con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");

    $profile_path = $data_path."/".$name;
    $thumb_path = $thumb_path."/".$name;

    mysqli_query($con,"INSERT INTO user_tb (user_id, social_login_type, nick_name, profile_image,thumbnail_image, birth_year, gender, phone_number) VALUES ($user_id,$social_login_type, $nick_name,'$profile_path', '$thumb_path', $birth_year,$user_gender,$phone_number)");

    mysqli_close($con);

  echo "성공";
 }else{
  echo "실패";
 }
}else{
 echo "파일없음";
}




    // header("Content-Type:text/html; charset=UTF-8");
    //
    // $userID = $_POST["userID"];
    // $userName = $_POST["userName"];
    // $userRegisterType = $_POST["userRegisterType"];
    //
    // $file= $_FILES['uploaded_file'];
    //
    // //이미지 파일을 영구보관하기 위해
    // //이미지 파일의 세부정보 얻어오기
    // $srcName= $file['name'];
    // $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
    //
    // //임시 저장소 이미지를 원하는 폴더로 이동
    //
    // $nowtime = "images/profile_image/".date('Ymd_his');
    //
    // $dstName= $nowtime.$srcName;
    // $result=move_uploaded_file($tmpName, $dstName);
    // if($result){
    //     echo "upload success\n";
    // }else{
    //     echo "upload fail\n";
    // }
    //
    // echo "$name\n";
    // echo "$msg\n";
    // echo "$dstName\n";
    //
    // if($nowtime === $dstName){
    //     $dstName = "images/profile_image/20201126_1029398142f53e51d2ec31bc0fa4bec241a919_crop.jpeg";
    // }
    //
    // // $name, $msg, $dstName, $now DB에 저장
    // // MySQL에 접속
    // $conn = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "test");
    //
    // //한글 깨짐 방지
    // mysqli_query($conn, "set names utf8");
    //
    //
    //
    // //insert하는 쿼리문
    // $sql="insert into testTable (name) values('$dstName')";
    //
    // $result =mysqli_query($conn, $sql); //쿼리를 요청하다.
    //
    // if($result) echo "insert success \n";
    // else echo "insert fail \n";
    //
    // mysqli_close($conn);


?>
