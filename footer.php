<?php

$gray='#878787';
$active='#a62626';

$page = basename($_SERVER['PHP_SELF'], '.php');

$color1=$gray;
$color2=$gray;
$color3=$gray;
$color4=$gray;
$color5=$gray;

if ($page=='login'){
    $color5=$active;

}elseif($page=='chat' || $page=='chat19' ){
    $color4=$active;

}elseif($page=='agahi'){
    $color3=$active;

}elseif($page=='cat'){
    $color2=$active;

}elseif($page=='index'){
    $color1=$active;
}

?>



<div class="divbottom">


<div class="bottom2">



<buttom class="btn_bottom">
<a href="index.php" style="color: <?php echo $color1; ?>; text-decoration: auto;">
<span><img src="divar2.png" style="width:32px;"></span>
<br>
<span style="font-size:13px; color:<?php echo $color1; ?>;">آگهی‌ها</span> 
</a>
</buttom>
   



<buttom class="btn_bottom" style="padding-top: 8px; ">
<a  style="color: <?php echo $color2; ?>; text-decoration: auto;">
<span style="text-align: center; margin-bottom: 4px;"><i class="fa-regular fa-list"></i></span>
<br>
<span style="font-size:13px; color: <?php echo $color2; ?>; margin-top: 4px; display: block; font-weight: 100;">
دسته‌ها
</span> 
</a>
</buttom>






<buttom class="btn_bottom" style="padding-top: 8px; ">
<a href="agahi.php" style="color: <?php echo $color3; ?>; text-decoration: auto;">
<span style="text-align: center; font-size: 20px;"><i class="fa-solid fa-circle-plus"></i></span>
<br>
<span style="font-size:12px; color: <?php echo $color3; ?>; display: block; font-weight: 100;">
ثبت‌آگهی
</span> 
</a>

</buttom>
   





<buttom class="btn_bottom" style="padding-top: 8px; ">
<a href="chat.php" style="color: <?php echo $color4; ?>; text-decoration: auto;">
<span style="text-align: center; font-size: 20px;"><i class="fa-solid fa-comment"></i></span>
<br>
<span style="font-size:12px; color: <?php echo $color4; ?>; display: block; font-weight: 100;">
چت
</span> 
</a>
</buttom>
   





<buttom class="btn_bottom" style="padding-top: 8px; ">
<a href="login.php" style="color: <?php echo $color5; ?>; text-decoration: auto;">
<span style="text-align: center; font-size: 20px;"><i class="fa-solid fa-user"></i></span>
<br>
<span style="font-size:12px; color: <?php echo $color5; ?>; display: block; font-weight: 100;">
دیوار من
</span> 
</a>
</buttom>
   



</div>

</div>