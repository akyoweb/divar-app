<?php

require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');

$onvan = '';
$karkard = '';
$price = '';
$image_name = '';
$form_message = '';
$message_type = '';
$daste = '';
$shahr = '';
$vaziat = '';

// مقادیر مجاز (Whitelist) دسته، شهر و وضعیت
$allowed_daste = array('املاک', 'وسایل نقلیه', 'کالای دیجیتال', 'خانه و آشپزخانه', 'خدمات', 'استخدام', 'شخصی', 'سایر');
$allowed_shahr = array('تهران', 'کرج', 'مشهد', 'اصفهان', 'شیراز', 'تبریز', 'سایر');
$allowed_vaziat = array('نو', 'در حد نو', 'کارکرده', 'نیاز به تعمیر');




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['onvan'], $_POST['karkard'])) {
    $onvan = trim((string) $_POST['onvan']);
    $karkard = trim((string) $_POST['karkard']);
    $price = trim((string) ($_POST['price'] ?? ''));
    $image_name = preg_replace('/[^0-9a-zA-Z._-]/', '', (string) ($_POST['image_name'] ?? ''));
    $daste = in_array($_POST['daste'] ?? '', $allowed_daste, true) ? $_POST['daste'] : '';
    $shahr = in_array($_POST['shahr'] ?? '', $allowed_shahr, true) ? $_POST['shahr'] : '';
    $vaziat = in_array($_POST['vaziat'] ?? '', $allowed_vaziat, true) ? $_POST['vaziat'] : '';

    $saat = 'دقایقی پیش در ' . ($shahr !== '' ? $shahr : 'تهران');

    $image_name2 = 'uploads/' . $image_name;

    if ($onvan === '' || $karkard === '') {
        $form_message = 'عنوان و توضیحات الزامی است.';
        $message_type = 'error';
    } elseif ($image_name === '') {
        $form_message = 'لطفا ابتدا تصویر آگهی را بارگذاری کنید.';
        $message_type = 'error';
    } else {
        $stmt = mysqli_prepare($db, 'INSERT INTO divar (onvan, karkard, price, time, img) VALUES (?,?,?,?,?)');
        mysqli_stmt_bind_param($stmt, 'sssss', $onvan, $karkard, $price, $saat, $image_name2);
        if (mysqli_stmt_execute($stmt)) {
            $form_message = 'آگهی با موفقیت ثبت شد.';
            $message_type = 'success';
            $onvan = '';
            $karkard = '';
            $price = '';
            $daste = '';
            $shahr = '';
            $vaziat = '';
            $image_name = '';
        } else {
            $form_message = 'خطا در ثبت آگهی.';
            $message_type = 'error';
        }
        mysqli_stmt_close($stmt);
    }
}






if (isset($_POST["submit_image"])) {


    $file = $_FILES["fileToUpload"] ?? null;
    $max_size = 5 * 1024 * 1024;
    $allowed_mimes = array('image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp');
    $upload_error = $file['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($upload_error !== UPLOAD_ERR_OK || (int) $file['size'] > $max_size) {
        $form_message = 'آپلود عکس نامعتبر است. حجم مجاز تا ۵ مگابایت است.';
        $message_type = 'error';
    } else {
        $image_info = @getimagesize($file['tmp_name']);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : false;
        if ($finfo) {
            finfo_close($finfo);
        }

        if (!$image_info || !isset($allowed_mimes[$mime])) {
            $form_message = 'فقط فایل تصویر معتبر مجاز است (JPG ، PNG یا WEBP).';
            $message_type = 'error';
        } else {
            $extension = $allowed_mimes[$mime];
            $image_name = random_int(10000000, 99999999) . '.' . $extension;

            if (!move_uploaded_file($file['tmp_name'], "uploads/" . $image_name)) {
                $image_name = '';
                $form_message = 'آپلود با خطا مواجه شد.';
                $message_type = 'error';
            } else {
                $form_message = 'تصویر با موفقیت بارگذاری شد. حالا اطلاعات آگهی را تکمیل کنید.';
                $message_type = 'success';
            }
        }
    }
}







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


    <div class="main_search" style="display: flex; align-items: center; gap: 10px;">
        <a href="index.php" class="back-btn" aria-label="بازگشت به صفحه اصلی">
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>
        <div style="font-weight: bold;">ثبت آگهی جدید</div>
    </div>


    <p style="padding: 20px; padding-bottom:0px;">اطلاعات زیر را تکمیل کنید:</p>

    <?php if ($form_message !== '') {
        $msg_color = ($message_type === 'success') ? '#1a8917' : '#be3737';
        $msg_bg = ($message_type === 'success') ? '#e8f5e9' : '#fdecea';
        ?>
        <div style="margin: 0 20px 12px; padding: 10px 14px; border-radius: 8px; font-size: 14px;
                    color: <?php echo $msg_color; ?>; background: <?php echo $msg_bg; ?>;">
            <?php echo htmlspecialchars($form_message, ENT_QUOTES, 'UTF-8'); ?>
            <?php if ($message_type === 'success' && strpos($form_message, 'آگهی') !== false) { ?>
                <a href="index.php"
                    style="display: inline-block; margin-top: 8px; color: #1a8917; font-weight: bold; text-decoration: underline;">مشاهده
                    آگهی‌ها ←</a>
            <?php } ?>
        </div>
    <?php } ?>



    <div class="main_search" style="box-shadow: none; padding-top: 0;">
        <p class="lb_divar">بارگذاری تصویر:</p>

        <?php

        if (strlen($image_name) > 6) {

            echo '
<div class="img_divar">
<img style="width: auto; max-height: 100px;" src="uploads/' . htmlspecialchars($image_name, ENT_QUOTES, 'UTF-8') . '">
</div>
';

        } else {
            echo '
               <form action="agahi.php" method="post" enctype="multipart/form-data">
               <p style="font-size:13px; color:gray; margin-bottom:8px;">فرمت JPG، PNG یا WEBP — حداکثر ۵ مگابایت</p>
               <input class="upload-control" type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg,image/png,image/webp" required>
               <input type="submit" class="btn btn-success" value="آپلود تصویر" name="submit_image">
            </form>
             ';
        }


        ?>


    </div>


    <form method="post" action="agahi.php" class="ad-page">

        <div class="main_search" style="box-shadow: none; padding-top: 0;">
            <p class="lb_divar">عنوان:</p>
            <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">

                <input type="text" value="<?php echo htmlspecialchars($onvan, ENT_QUOTES, 'UTF-8'); ?>" name="onvan"
                    placeholder="عنوان آگهی" maxlength="100" minlength="3" class="form-control-divar" autocomplete="off"
                    required>

                <input type="hidden" name="image_name"
                    value="<?php echo htmlspecialchars($image_name, ENT_QUOTES, 'UTF-8'); ?>">

            </div>

        </div>



        <div class="main_search" style="box-shadow: none; padding-top: 0;">
            <p class="lb_divar">توضیحات</p>
            <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">

                <textarea name="karkard" placeholder="توضیحات را وارد کنید" maxlength="1000" minlength="5"
                    class="form-control-divar"
                    required><?php echo htmlspecialchars($karkard, ENT_QUOTES, 'UTF-8'); ?></textarea>

            </div>

        </div>



        <div class="main_search" style="box-shadow: none; padding-top: 0;">
            <p class="lb_divar">قیمت:</p>
            <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">

                <input type="text" value="<?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?>" name="price"
                    placeholder="قیمت را وارد کنید (اختیاری)" maxlength="20" inputmode="numeric" pattern="[0-9۰-۹, ]*"
                    class="form-control-divar">

            </div>

        </div>




























        <br>
        <br>
        <br>
        <div class="divbottom2">


            <button type="submit" tabindex="0" class="btn_divar"><span>ثبت آگهی</span></button>

        </div>



    </form>


    <?php include("footer.php") ?>

</body>

</html>