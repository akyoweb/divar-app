<?php
$gray = '#878787'; $active = '#a62626';
$page = basename($_SERVER['PHP_SELF'], '.php');
$isHome = $page === 'index'; $isAd = $page === 'agahi'; $isChat = in_array($page, ['chat', 'chat19'], true); $isLogin = $page === 'login';
?>
<nav class="divbottom" aria-label="ناوبری اصلی">
  <div class="bottom2">
    <a class="btn_bottom" href="index.php" style="color:<?php echo $isHome ? $active : $gray; ?>"><i class="fa-solid fa-house"></i><span>آگهی‌ها</span></a>
    <a class="btn_bottom" href="index.php#categories" style="color:<?php echo $isHome ? $active : $gray; ?>"><i class="fa-solid fa-list"></i><span>دسته‌ها</span></a>
    <a class="btn_bottom" href="agahi.php" style="color:<?php echo $isAd ? $active : $gray; ?>"><i class="fa-solid fa-circle-plus"></i><span>ثبت‌آگهی</span></a>
    <a class="btn_bottom" href="chat.php" style="color:<?php echo $isChat ? $active : $gray; ?>"><i class="fa-solid fa-comment"></i><span>چت</span></a>
    <a class="btn_bottom" href="login.php" style="color:<?php echo $isLogin ? $active : $gray; ?>"><i class="fa-solid fa-user"></i><span>دیوار من</span></a>
  </div>
</nav>