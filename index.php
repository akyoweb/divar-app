<?php

require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');


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
    <div class="search_divar">
        <i class="fa-solid fa-magnifying-glass search1"></i>
        <label class="search_text">جستجو در همهٔ آگهی‌ها</label>
        <label class="search_text2">تهران <i class="fa-regular fa-location-dot"></i></label>
    </div>
</div>


<div class="filter1">

    <div class="filter2">

        <button> <i class="fa-regular fa-sliders" style="font-size: 12px;"></i> فیلترها</button>
        <button> <i class="fa-regular fa-list" style="font-size: 12px;"></i> دسته‌ها</button>
        <button> </i> خودروسازی</button>
        <button></i> فروش آپارتمان</button>
        <button></i> اجاره آپارتمان</button>
        <button></i> موبایل </button>
        <button></i> صندلی و نیمکت </button>
    </div>
</div>
<br>
<h1 class="h1">دیوار تهران:‌ انواع آگهی‌ها و خدمات در تهران</h1>
 











<?php








$sql= mysqli_query($db," select * from divar order by id desc; ");

 

while ($row=mysqli_fetch_assoc($sql)){
$name=$row['onvan'];
$karkard=$row['karkard'];
$price=$row['price'];
$saat=$row['time'];
$image=$row['img'];








echo '
<div class="agahi1">

<div class="agahi2">

    <div class="row" >

        <div class="col-6">
            <div class="text1">
                '. $name.'
            </div>
            <br> 
            <br> 
             <div class="text2"  >'. $karkard.'</div> 
            <div class="text2">'. $price.'</div>
            <div class="text2" style="font-size:12px ; color:rgb(160, 160, 160)">'. $saat.'</div> 
            
        </div>
        <div class="col-6">
            <div>

            </div>
            <img src="'. $image.'" alt="">
        </div>

      



    </div> 

</div> 
</div>
';


}

?>


























<!-- <div class="agahi1">

<div class="agahi2">

    <div class="row" >

        <div class="col-6">
            <div class="text1"> دینام موتور کولر آبی(گارانتی شرکتی) </div>
            <br>  
             <div class="text2"  > نو </div> 
            <div class="text2">۷۰۰,۰۰۰ تومان</div>
            <div class="text2" style="font-size:12px ; color:rgb(160, 160, 160)">
                <span class="redtext">فروشگاه </span> 
                مرکز پخش دینام...
            </div> 
            
        </div>
        <div class="col-6">
            <div>

            </div>
            <img src="https://s100.divarcdn.com/static/thumbnails/1688764781/AZyRggqE.webp" alt="">
        </div>

      



    </div> 

</div> 
</div> -->





<!-- <div class="agahi1">

    <div class="agahi2">
    
        <div class="row" >
    
            <div class="col-6">
                <div class="text1"> انواع تندیس کریستال در طرح...</div>
                <br>  
                 <div class="text2"  > در حد نو </div> 
                <div class="text2">۳۵,۰۰۰ تومان</div>
                <div class="text2" style="font-size:12px ; color:rgb(160, 160, 160)">لحظاتی پیش در جمهوری</div> 
                
            </div>
            <div class="col-6">
                <div>
    
                </div>
                <img src="https://s100.divarcdn.com/static/thumbnails/1691091133/AYBUMPQw.webp" alt="">
            </div>
    
          
    
    
    
        </div> 
    
    </div> 
    </div> -->


    <br>
    <br>
    <br>

    <?php include("footer.php") ?>
    
</body>
</html>