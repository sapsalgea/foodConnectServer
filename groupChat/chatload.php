<?php


$con = mysqli_connect("localhost", "DB_USER_ID", "DB_PASSWORD", "food_connect_db");
mysqli_query($con, "set names utf8");
date_default_timezone_set("Asia/Seoul");

$roomid = $_GET['roomId'];

$userIndex = $_GET['userIndex'];
$pageNum = 0;

if ($_GET['pageEnd'] != 0) {
    $list = $_GET['pageEnd'];
} else {
    $list = 40;
}



$sql = "SELECT gmt.*,rt.join_users, u.account_delete 
FROM group_message_tb AS gmt 
INNER JOIN (SELECT * 
                FROM join_room_tb 
                WHERE user_index = '$userIndex' 
                AND room_id = '$roomid') AS jru 
ON jru.join_datetime < gmt.sendtime 
JOIN (SELECT join_users 
        FROM room_tb 
        WHERE id = '$roomid') AS rt 
JOIN user_tb AS u 
ON gmt.user_index = u.id 
WHERE gmt.to_room_id = '$roomid' 
ORDER BY sendtime desc 
LIMIT $pageNum, $list";
$memberSQL = "SELECT join_users FROM room_tb Where id = $roomid";
$memberResult = mysqli_query($con, $memberSQL);
$memberrow = $memberResult->fetch_assoc();
$result = mysqli_query($con, $sql);
$response = array();
$ChatLogList = array();
if (mysqli_num_rows($result) > 0) {

    while ($row = $result->fetch_assoc()) {
        if ($row['account_delete'] == "1") {
            $array = array(
                "type" => $row['message_type'],
                "from" => "(탈퇴한 유져)",
                "to" => $row['to_room_id'],
                "content" => $row['content'],
                "thumbnailImage" => "images/deletedUserProfileImage/deleted_profile_default_image.jpg",
                "sendTime" => $row['sendtime'],
                "members" => $row['join_members']
            );
        } else {
            $array = array(
                "type" => $row['message_type'],
                "from" => $row['from_user_id'],
                "to" => $row['to_room_id'],
                "content" => $row['content'],
                "thumbnailImage" => $row['thumbnailImage'],
                "sendTime" => $row['sendtime'],
                "members" => $row['join_members']
            );
        }
        array_push($ChatLogList, $array);
        $response['members'] = $row['join_users'];
    }
    $response['success'] = true;
    $response['snum'] = $pageNum;
    $response['enum'] = $list;
    $response['ChatLogList'] = $ChatLogList;


    echo json_encode($response, JSON_UNESCAPED_UNICODE);
} else {
    $response['success'] = false;
    $response['ChatLogList'] = $ChatLogList;
    $response['members'] = $memberrow['join_users'];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
