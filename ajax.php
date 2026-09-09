<?php


require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');



$chat=$_GET['chat'];
$from_user_id=$_GET['user_id'];
$to_user_id='1';


if ($from_user_id=='1'){
    $to_user_id='19';
}else{
    $to_user_id='1';
}

$sql= mysqli_query($db, "insert into chat (chattext,from_user_id,to_user_id) values('$chat','$from_user_id','$to_user_id') ");

 

echo $chat;




?>