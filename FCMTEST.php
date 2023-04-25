<?php
$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header = array("Content-Type:application/json", "Authorization:key=FireBaseServerKey");
$data = json_encode(array(
    "to" =>"dPXqaT0nRsamRbaHmsAQCX:APA91bEOyEYMHpNSY7hJLqKTd8lEddsGZpN3l3nIwFX9Z1a6FplDOaso5M7VnuyhZnKMKRuzlVOkFIil2SsGJGSNeC-oZkZkLgjJgzOqC3_dy0LiXVGGfDyFAysvRZK9sjrcbBUJ96fB",
    "priority" => "high",
    "data" => array(
        "title"   => "d.",
        "body" => "방에 참여신청이 왔습니다.",
        "roomId" => "dd"
    )
));

// $ch = curl_init("https://fcm.googleapis.com/fcm/send");
//
// $data = json_encode(array(
//     "to" => $hostToken,
//     "priority" => "high",
//     "data" => array(
//         "title"   => "참여 신청이 왔습니다.",
//         "body" => $row['room_title']."방에 참여신청이 왔습니다.",
//         "roomId" => $row['id']
//     )
// ));










curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_exec($ch);
?>
