<?php
// 원본 이미지를 불러온다.



 $data_path = "images/profile_image";

 $thumb_path = "images/profile_image_thumbnail";

 // @mkdir($thumb_path, 0707);
 // @chmod($thumb_path, 0707);

 $org_file = "dd.png";
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
?>
