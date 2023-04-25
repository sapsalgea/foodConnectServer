<?php

//파일 받기
$chatImage = $_FILES['uploaded_file']['name'];


// 저장할 경로
$file_dir =$_SERVER['DOCUMENT_ROOT'].'/images/directMessageImage/';
$tempData = $_FILES['uploaded_file']['tmp_name'];
$name = basename($_FILES["uploaded_file"]["name"]);



// $chatImage = $_FILES['uploaded_file']['name'];
// $file= $_FILES['uploaded_file'];
//
// //이미지 파일을 영구보관하기 위해
// //이미지 파일의 세부정보 얻어오기
// $srcName= $file['name'];
// $tmpName= $file['tmp_name']; //php 파일을 받으면 임시저장소에 넣는다. 그곳이 tmp
// //임시 저장소 이미지를 원하는 폴더로 이동
// $dstName= "images/directMessageImage/".date('Ymd_his').$srcName;



$uploadsResult = array();
// // 임시폴더에서  ->  경로 이동 .파일이름

if(isset($chatImage)){
 if(move_uploaded_file($tempData, $file_dir.date('Ymd_his').$name)){


   $response['success'] = "true";
   $response['ImageName'] = "/images/directMessageImage/".date('Ymd_his').$name;

   echo json_encode($response);
 }else{

   $response['success'] = "false";
   $response['ImageName'] = $chatImage;

   echo json_encode($response);
 }
}else{
  $response['success'] = "false";
  $response['ImageName'] = null;

  echo json_encode($response);
}



?>
