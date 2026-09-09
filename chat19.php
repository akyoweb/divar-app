<?php

require("db.php"); 
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');

$mobile="";
$code="";


$user_id='19';

$buttontext='تأیید';

if (isset($_POST['mobile']) && isset($_POST['code'])==false  ){

    $mobile= $_POST['mobile'];

    $code=random_int(100000,999999);

    $sql = mysqli_query($db, " insert into smscode (mobile, smscode) values ('$mobile' , '$code')  " );


    
    $APIKey = "wedwefweeeeeef";
    $SecretKey = "wefwefwefwewewew";
    
       
    try {
      
    $data = array(
        "ParameterArray" => array(
            
             array(
                "Parameter" => "code1",
                "ParameterValue" => "$code" 
            ),  
            array(
                "Parameter" => "name1",
                "ParameterValue" => 'آکادمی مهارت آیولرن'
            ) 
        ),
        "Mobile" => $mobile,
        "TemplateId" => "4430"
    );
    
    
       
    $SmsIR_UltraFastSend = new SmsIR_UltraFastSend($APIKey,$SecretKey);
    $UltraFastSend = $SmsIR_UltraFastSend->UltraFastSend($data);
    $sms_status=$UltraFastSend;
    
    //  var_dump($UltraFastSend); 
    } catch (Exception $e) {
    echo 'Error UltraFastSend : '.$e->getMessage();
    }
    
    
    
    echo  $sms_status.'<br>'; 
    
    






    $buttontext='ورود';
}


if (isset($_POST['mobile']) && isset($_POST['code']) && strlen($_POST['code'])==6){

    $mobile= $_POST['mobile'];
    $code=$_POST['code'];
    $code_org='';

$sql2=mysqli_query($db,"

select * from smscode where mobile = '$mobile' order by id desc limit 1

");

if ($row=mysqli_fetch_assoc($sql2)){
    $code_org=$row['smscode'];
}

// echo $code_org;


if ($code_org == $code){
    $sql = mysqli_query($db, " insert into user (mobile, password) values ('$mobile' , '$code')  " );

echo 'کد صحیح است';
echo '<meta http-equiv="refresh" content="1; url=agahi.php">';

}else{
    echo 'کد اشتباه است';

}



}










 
  



?>

<html>
<head>
   
<meta charset="utf-8">
<title>چت آیولرن</title>
  <meta name="viewport" content="viewport-fit=cover,width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"> 

  <link href="bootstrap.min.css" rel="stylesheet" > 
 
 
  <!-- اینجا دقت کنید که Font Awesome 
  رو درست بنویسید -->
  <link rel="stylesheet" href="fontawesome/css/all.min.css"   />
 

  <link rel="stylesheet" href="style.css">


  <link rel="icon" type="image/png" sizes="32x32" href="divar.png">

</head>

<body class="main"> 


<div class="main_search">
<div style="font-weight: bold;">صفحه چت  </div>
</div>
   

<div id = "chatmain">


<?php


$sql=mysqli_query($db , " select * from chat ; "  );


while ($row = mysqli_fetch_assoc($sql)){

 
    if ($row['from_user_id']=='19'){
        echo '<div class="my_chatblock"><div class="my_chat"><p class="chat1">'.$row['chattext'].'</p></div></div>'; 
    }elseif ($row['from_user_id']=='1'){
        echo '<div class="your_chatblock"><div class="your_chat"><p class="chat1">'.$row['chattext'].'</p></div></div>'; 
    }
   


}



?>









<!-- <div class="my_chatblock"><div class="my_chat"><p class="chat1">متن</p></div></div> -->
 

<!-- <div class="your_chatblock">
<div class="your_chat">

<p class="chat1">
سلام من میخواستم در مورد آگهی شما نظر بدم.
</p>

</div>
</div> -->
 





</div>












 


    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="divbottom2">

     
<div class="chat_text1"  >
    <div class="chat_text2" >
        <i onclick="send()" id="chat_button" class="fa-solid fa-send search1" style="line-height: 54px; font-size: 20px;"></i> 

        <input id="chat" type="text" value="<?php   ?>" name="chat" placeholder="متنی بنویسید" maxlength="200" class="chat_txt" onkeyup="send_ok(event)">
       
    </div>

 







</div>

    </div>

 


<?php include("footer.php") ?>



<script>


function send_ok(event){


let chattext= document.getElementById("chat").value;


document.getElementById("chat_button").style.color="#a62626";

 
if (chattext.length<1){
    document.getElementById("chat_button").style.color="rgba(0,0,0,.32)";  
}

   
if (event.keyCode == 13){
send();
}
}




function send() {
    chattext=document.getElementById("chat").value;

    
    if (chattext.length<1){
        return;
    }
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

       document.getElementById("chat").value='';
       document.getElementById("chat_button").style.color="rgba(0,0,0,.32)";

        document.getElementById("chatmain").innerHTML=document.getElementById("chatmain").innerHTML+ '<div class="my_chatblock"><div class="my_chat"><p class="chat1">'+this.responseText+'</p></div></div>';
        window.scrollTo(0, document.body.scrollHeight);
      }
    };


    xmlhttp.open("GET", "ajax.php?chat=" + chattext + '&user_id='+ <?php echo $user_id ; ?>, true);
    xmlhttp.send();
 
}



daryaft();


window.scrollTo(0, document.body.scrollHeight);


function daryaft() {

setInterval(
        
        
function(){


 
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {

    if (this.responseText.length>0){
 
    document.getElementById("chatmain").innerHTML=document.getElementById("chatmain").innerHTML+ '<div class="your_chatblock"><div class="your_chat"><p class="chat1">'+this.responseText+'</p></div></div>';

    window.scrollTo(0, document.body.scrollHeight);

}

  }
};


xmlhttp.open("GET", "daryaft.php?user_id="+ <?php echo $user_id ; ?>, true);
xmlhttp.send();


}    
        
, 1000);

}


</script>




</body>
</html>