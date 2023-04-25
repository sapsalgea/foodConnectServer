<?php


$chatImage = $_FILES['uploaded_file']['name'];
$roomId = $_POST['roomId'];
$userIndex = $_POST['roomIndex'];
$userNickname = $_POST['userNickname'];


$file_dir = str_replace('"','',$_SERVER['DOCUMENT_ROOT'] . "/images/groupMessageImage/$roomId/");
$file_split = explode(".", $chatImage);                    //파일명 . 기준 스플릿
$file_ex1 = $file_split[count($file_split) - 2.3];          //확장자에서 파일명만 표시
$file_ex2 = $file_split[count($file_split) - 1];            //확장자만 표시
$tempData = $_FILES['uploaded_file']['tmp_name'];

if(!is_dir($file_dir)){
    umask(0);
    mkdir($file_dir,0777,true);
}
$name = date('Ymd_his').'_'.$userNickname.'.'.$file_ex2;
$path = str_replace('"','',$file_dir . $name);
if (isset($chatImage)) {
    if (move_uploaded_file($tempData, $path)) {


        $response['success'] = "true";
        $response['ImageName'] = str_replace('"','',"images/groupMessageImage/$roomId/$name");

        echo json_encode($response);
    } else {

        $response['success'] = "false";
        $response['ImageName'] = str_replace('"','',"images/groupMessageImage/$roomId/$name");

        echo json_encode($response);
    }
} else {
    $response['success'] = "false";
    $response['ImageName'] = null;

    echo json_encode($response);
}
