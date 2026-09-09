<?php

require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');


?>

<html>

<head>

    <meta charset="utf-8">
    <title>دیوار تهران: مرجع انواع نیازمندی و آگهی‌های نو و دست دو در شهر تهران</title>
    <meta name="viewport"
        content="viewport-fit=cover,width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link href="bootstrap.min.css" rel="stylesheet">


    <!-- اینجا دقت کنید که Font Awesome
  رو درست بنویسید -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css" />


    <link rel="stylesheet" href="style.css">


    <link rel="icon" type="image/png" sizes="32x32" href="divar.png">

</head>

<body class="main">


    <header class="site-header">
        <a class="brand" href="index.php" aria-label="صفحه اصلی دیوار">دیوار</a>
        <div class="header-actions">
            <button class="location-btn" id="locationButton" type="button" aria-haspopup="dialog">تهران <span
                    aria-hidden="true">⌄</span></button>
            <a class="login-link" href="login.php">ورود</a>
            <a class="post-btn" href="agahi.php">ثبت آگهی</a>
        </div>
    </header>

    <main class="page-content">
        <div class="search-box" role="search">
            <span class="search-icon" aria-hidden="true">⌕</span>
            <input id="searchInput" type="search" placeholder="جستجو در همهٔ آگهی‌ها" aria-label="جستجو در آگهی‌ها">
            <span class="search-location">تهران</span>
        </div>

        <section class="category-section" id="categories" aria-labelledby="categories-title">
            <div class="section-heading">
                <h2 id="categories-title">دسته‌بندی‌ها</h2><button id="toggleCategories" class="text-button"
                    type="button">نمایش همه</button>
            </div>
            <div class="category-grid" id="categoryGrid">
                <button class="category-card active" data-category="all" type="button"><span>▦</span><b>همه</b></button>
                <button class="category-card" data-category="املاک" type="button"><span>⌂</span><b>املاک</b></button>
                <button class="category-card" data-category="وسایل نقلیه" type="button"><span>▱</span><b>وسایل
                        نقلیه</b></button>
                <button class="category-card" data-category="کالای دیجیتال" type="button"><span>▣</span><b>کالای
                        دیجیتال</b></button>
                <button class="category-card" data-category="خانه و آشپزخانه" type="button"><span>⌑</span><b>خانه و
                        آشپزخانه</b></button>
                <button class="category-card" data-category="خدمات" type="button"><span>⚒</span><b>خدمات</b></button>
                <button class="category-card" data-category="استخدام"
                    type="button"><span>♙</span><b>استخدام</b></button>
                <button class="category-card" data-category="شخصی" type="button"><span>♡</span><b>شخصی</b></button>
            </div>
        </section>
        <div class="filter-bar"><span id="resultCount">جدیدترین آگهی‌ها</span>
            <div class="filter-wrap"><button class="filter-button" id="filterButton" type="button">☷ فیلترها</button>
                <div class="filter-panel" id="filterPanel"><label class="filter-option"><input type="radio" name="sort"
                            value="new" checked> جدیدترین</label><label class="filter-option"><input type="radio"
                            name="sort" value="cheap"> ارزان‌ترین</label><label class="filter-option"><input
                            type="radio" name="sort" value="expensive"> گران‌ترین</label></div>
            </div>
        </div>
        <br>
        <h1 class="h1">دیوار تهران: انواع آگهی‌ها و خدمات در تهران</h1>












        <?php








        $ads = array();
        if (isset($db) && $db instanceof mysqli) {
            $sql = mysqli_query($db, "SELECT * FROM divar ORDER BY id DESC");
            if ($sql) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $ads[] = $row;
                }
            }
        }
        if (!$ads) {
            echo '<div class="empty-state"><strong>هنوز آگهی‌ای ثبت نشده است</strong><span>اولین آگهی را شما ثبت کنید.</span><a href="agahi.php" class="post-btn">ثبت آگهی</a></div>';
        }
        foreach ($ads as $row) {
            $name = htmlspecialchars($row['onvan'] ?? '', ENT_QUOTES, 'UTF-8');
            $karkard = htmlspecialchars($row['karkard'] ?? '', ENT_QUOTES, 'UTF-8');
            $price = htmlspecialchars($row['price'] ?? '', ENT_QUOTES, 'UTF-8');
            $saat = htmlspecialchars($row['time'] ?? '', ENT_QUOTES, 'UTF-8');
            $image = htmlspecialchars($row['img'] ?? '', ENT_QUOTES, 'UTF-8');
            $searchText = htmlspecialchars(mb_strtolower($name . ' . ' . $karkard, 'UTF-8'), ENT_QUOTES, 'UTF-8');








            echo '
<article class="agahi1" data-search="' . $searchText . '" data-category="' . $karkard . '" data-price="' . preg_replace('/[^0-9]/', '', $price) . '">

<div class="agahi2">

    <div class="ad-content">

        <div class="ad-details">
            <div class="text1">
                ' . $name . '
            </div>
            <br>
            <br>
             <div class="text2"  >' . $karkard . '</div>
            <div class="text2">' . $price . '</div>
            <div class="text2" style="font-size:12px ; color:rgb(160, 160, 160)">' . $saat . '</div>

        </div>
                <div class="ad-image-wrap">
                    ' . ($image ? '<img src="' . $image . '" alt="' . $name . '">' : '<div class="image-placeholder">بدون تصویر</div>') . '
                </div>

            </div>

        </div>
        </article>
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


    </main>
    <div class="city-modal" id="cityModal" role="dialog" aria-modal="true" aria-labelledby="cityTitle">
        <div class="city-panel">
            <div class="section-heading">
                <h3 id="cityTitle">انتخاب شهر</h3><button type="button" class="text-button" id="closeCity">بستن</button>
            </div>
            <div class="city-list"><button type="button" data-city="تهران">تهران</button><button type="button"
                    data-city="کرج">کرج</button><button type="button" data-city="مشهد">مشهد</button><button
                    type="button" data-city="اصفهان">اصفهان</button><button type="button"
                    data-city="شیراز">شیراز</button><button type="button" data-city="تبریز">تبریز</button><button
                    type="button" data-city="رشت">رشت</button><button type="button" data-city="قم">قم</button><button
                    type="button" data-city="اهواز">اهواز</button></div>
        </div>
    </div>
    <script>
        (() => {
            const input = document.getElementById('searchInput');
            const cards = [...document.querySelectorAll('.agahi1[data-search]')];
            const count = document.getElementById('resultCount'); let category = 'all';
            const cityModal = document.getElementById('cityModal'); const locationButton = document.getElementById('locationButton');
            locationButton.addEventListener('click', () => cityModal.classList.add('open')); document.getElementById('closeCity').addEventListener('click', () => cityModal.classList.remove('open'));
            cityModal.addEventListener('click', event => { if (event.target === cityModal) cityModal.classList.remove('open'); });
            cityModal.querySelectorAll('[data-city]').forEach(button => button.addEventListener('click', () => { const city = button.dataset.city; locationButton.innerHTML = `${city} <span aria-hidden="true">⌄</span>`; document.querySelector('.search-location').textContent = city; cityModal.classList.remove('open'); localStorage.setItem('divar_city', city); }));
            const savedCity = localStorage.getItem('divar_city'); if (savedCity) { locationButton.innerHTML = `${savedCity} <span aria-hidden="true">⌄</span>`; document.querySelector('.search-location').textContent = savedCity; }
            const filterButton = document.getElementById('filterButton'); const filterPanel = document.getElementById('filterPanel'); filterButton.addEventListener('click', () => filterPanel.classList.toggle('open'));
            document.querySelectorAll('input[name="sort"]').forEach(option => option.addEventListener('change', () => { const list = [...document.querySelectorAll('.agahi1')]; const value = option.value; list.sort((a, b) => value === 'cheap' ? (parseInt(a.dataset.price || '0') - parseInt(b.dataset.price || '0')) : value === 'expensive' ? (parseInt(b.dataset.price || '0') - parseInt(a.dataset.price || '0')) : 0); list.forEach(item => item.parentNode.appendChild(item)); }));
            const update = () => {
                const query = (input.value || '').trim().toLowerCase(); let visible = 0;
                cards.forEach(card => { const show = (!query || card.dataset.search.includes(query)) && (category === 'all' || card.dataset.category.includes(category)); card.hidden = !show; if (show) visible++; });
                count.textContent = visible ? `${visible} آگهی` : 'آگهی‌ای پیدا نشد';
            };
            input.addEventListener('input', update);
            document.querySelectorAll('.category-card').forEach(button => button.addEventListener('click', () => { document.querySelectorAll('.category-card').forEach(item => item.classList.remove('active')); button.classList.add('active'); category = button.dataset.category; update(); }));
            document.getElementById('toggleCategories').addEventListener('click', event => { const grid = document.getElementById('categoryGrid'); grid.classList.toggle('expanded'); event.currentTarget.textContent = grid.classList.contains('expanded') ? 'نمایش کمتر' : 'نمایش همه'; });
        })();
    </script>
    <?php include("footer.php") ?>


</body>

</html>