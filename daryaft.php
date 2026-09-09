<?php


require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');



$from_user_id=$_GET['user_id'];
$to_user_id='1';


if ($from_user_id=='1'){
    $to_user_id='19';
}else{
    $to_user_id='1';
}

$sql= mysqli_query($db, "select * from chat where from_user_id ='$to_user_id' and view1='0'; ");


if ($row=mysqli_fetch_assoc($sql)){
 

    $chatid=$row['id'];


    $sql2=mysqli_query($db, " update `chat` set view1='1' where id='$chatid'; ");
 

    echo $row['chattext'];

}
 






?>