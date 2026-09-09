<?php

require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');

$onvan='';
$karkard='';
$price='';
$image_name= '';


 

if (isset($_POST['onvan']) && isset($_POST['karkard'])){
$onvan=$_POST['onvan'];
$karkard=$_POST['karkard'];
$price=$_POST['price'];
$image_name=$_POST['image_name'];

$saat='دقایقی پیش در تهران';

$image_name2='uploads/'. $image_name;

$sql = mysqli_query($db, " 

insert into divar (onvan, karkard,price,time,img) values ('$onvan' , '$karkard' , '$price', '$saat', '$image_name2')  

" );


}






if(isset($_POST["submit_image"])) {


    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    
    $uploadOk = 1;

    $image_name= random_int(10000000,99999999).'.jpg';

    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "uploads/".$image_name)) {
        "آپلود عکس با موفقیت انجام شد.";




    
    } else {
    
        echo "آپلود با خطا مواجه شد.";
    }
    
    
}



 



?>

<html>
<head>
   
<meta charset="utf-8">
<title>دیوار تهران: مرجع انواع نیازمندی و آگهی‌های نو و دست دو در شهر تهران</title>
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
<div style="font-weight: bold;">ثبت آگهی جدید</div>
</div>
  

<p style="padding: 20px; padding-bottom:0px;">اطلاعات زیر را تکمیل کنید:</p>



<div class="main_search" style="box-shadow: none; padding-top: 0;" >
<p   class="lb_divar">بارگذاری تصویر:</p> 
    
<?php 

if (strlen($image_name)>6){

echo'
<div class="img_divar">
<img style="width: auto; max-height: 100px;" src="uploads/'.$image_name.'">
</div>
';

}else{
    echo '
    <form action="agahi.php" method="post" enctype="multipart/form-data"> 
    <input style="width: 65%;" type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" class="btn btn-success" value="آپلود تصویر" name="submit_image">
  </form>
  ';
}


?>


</div>


<form method="post" action="agahi.php">
 
<div class="main_search" style="box-shadow: none; padding-top: 0;" >
<p   class="lb_divar">عنوان:</p> 
    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
      
        <input type="text" value="<?php echo $onvan; ?>" name="onvan" placeholder="عنوان آگهی" maxlength="100" class="in_divar">

        <input type="hidden" name="image_name"  value="<?php echo $image_name; ?>">
       
    </div>
 
</div>
 


<div class="main_search" style="box-shadow: none; padding-top: 0;" >
<p   class="lb_divar">توضیحات</p> 
    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
      
        <input  type="text"  value="<?php echo $karkard; ?>" name="karkard" placeholder="توضیحات را وارد کنید" maxlength="100" class="in_divar">
       
    </div>
 
</div>



<div class="main_search" style="box-shadow: none; padding-top: 0;" >
<p   class="lb_divar">قیمت:</p> 
    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
      
        <input  type="tel"  value="<?php echo $price; ?>" name="price" placeholder="قیمت را وارد کنید" maxlength="100" class="in_divar">
       
    </div>
 
</div>






<div class="main_search" style="box-shadow: none; padding-top: 0;" >
<p   class="lb_divar">دسته ها</p> 
    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
      
        <input  type="tel"  value="" name="" placeholder="دسته بندی مورد نظر حودرا وارد کنید" maxlength="100" class="in_divar">
       
    </div>
 
</div>


















 


    <br>
    <br>
    <br>
    <div class="divbottom2">

    
<button type="submit" tabindex="0" class="btn_divar"><span >ثبت آگهی</span></button>

    </div>



</form>


<?php include("footer.php") ?>
    
</body>
</html>