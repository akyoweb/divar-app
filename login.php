<?php
session_start();
require("db.php ");
// require("../sms_api.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');

$mobile = "";
$code = "";
$form_message = '';
$message_type = '';
$step = 'mobile';

$buttontext = 'تأیید';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mobile']) && !isset($_POST['code'])){

    $mobile = preg_replace('/[^0-9]/', '', (string)$_POST['mobile']);
    if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
        $mobile = '';
        $form_message = 'شماره موبایل نامعتبر است. شماره‌ای مانند ۰۹۱۲۳۴۵۶۷۸۹ وارد کنید.';
        $message_type = 'error';
    }

    $code=random_int(100000,999999);

    $step = 'code';
    $buttontext = 'ورود';

    $stmt = mysqli_prepare($db, 'INSERT INTO smscode (mobile, smscode, created_at) VALUES (?, ?, NOW())');
    mysqli_stmt_bind_param($stmt, 'ss', $mobile, $code);
    mysqli_stmt_execute($stmt);



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
                "ParameterValue" => 'akyo'
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
        $form_message = 'خطا در ارسال پیامک. دوباره تلاش کنید.';
        $message_type = 'error';
        }

        // اگر ارسال پیامک موفق بود، پیام موفقیت نمایش داده می‌شود
        if ($message_type === '') {
            $form_message = 'کد تأیید به شماره ' . $mobile . ' پیامک شد.';
            $message_type = 'success';
        }
    }


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mobile'], $_POST['code'])) {
    $mobile = preg_replace('/[^0-9]/', '', (string)$_POST['mobile']);
    $code = (string)$_POST['code'];
    $step = 'code';
    $buttontext = 'ورود';
    $code_org = '';
    $code_id = null;

    if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
        $form_message = 'شماره موبایل نامعتبر است.';
        $message_type = 'error';
    } elseif (!preg_match('/^[0-9]{6}$/', $code)) {
        $form_message = 'کد باید ۶ رقم باشد.';
        $message_type = 'error';
    } else {

        $stmt = mysqli_prepare($db, 'SELECT id, smscode FROM smscode WHERE mobile = ? AND created_at >= (NOW() - INTERVAL 5 MINUTE) ORDER BY id DESC LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $mobile);
        mysqli_stmt_execute($stmt);
        $sql2 = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($sql2)) {
            $code_id = (int) $row['id'];
            $code_org = (string) $row['smscode'];
        }
        mysqli_stmt_close($stmt);

        if ($code_id !== null && hash_equals($code_org, $code)) {
            $stmt = mysqli_prepare($db, 'SELECT id FROM user WHERE mobile = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $mobile);
            mysqli_stmt_execute($stmt);
            $user_result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($user_result);
            mysqli_stmt_close($stmt);

            if ($user) {
                $user_id = (int) $user['id'];
            } else {
                $stmt = mysqli_prepare($db, 'INSERT INTO user (mobile, password) VALUES (?, ?)');
                $passwordHash = password_hash($code, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, 'ss', $mobile, $passwordHash);
                mysqli_stmt_execute($stmt);
                $user_id = mysqli_insert_id($db);
                mysqli_stmt_close($stmt);
            }

            // کد مصرف‌شده پاک می‌شود تا دوباره قابل استفاده نباشد
            $stmt = mysqli_prepare($db, 'DELETE FROM smscode WHERE mobile = ?');
            mysqli_stmt_bind_param($stmt, 's', $mobile);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['mobile'] = $mobile;

            header('Location: agahi.php');
            exit;
                } else {
                    $form_message = 'کد وارد شده اشتباه یا منقضی شده است.';
                    $message_type = 'error';
                }
            }
        }













class SmsIR_UltraFastSend
{
    /**
     * gets API Ultra Fast Send Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIUltraFastSendUrl()
    {
        return "http://RestfulSms.com/api/UltraFastSend";
    }
    /**
     * gets Api Token Url.
     *
     * @return string Indicates the Url
     */
    protected function getApiTokenUrl()
    {
        return "http://RestfulSms.com/api/Token";
    }
    /**
     * gets config parameters for sending request.
     *
     * @param string $APIKey API Key
     * @param string $SecretKey Secret Key
     * @return void
     */
    public function __construct($APIKey, $SecretKey)
    {
        $this->APIKey = $APIKey;
        $this->SecretKey = $SecretKey;
    }
    /**
     * Ultra Fast Send Message.
     *
     * @param data[] $data array structure of message data
     * @return string Indicates the sent sms result
     */
    public function UltraFastSend($data)
    {
        $token = $this->GetToken($this->APIKey, $this->SecretKey);
        if ($token != false) {
            $postData = $data;
            $url = $this->getAPIUltraFastSendUrl();
            $UltraFastSend = $this->execute($postData, $url, $token);
            $object = json_decode($UltraFastSend);
            if (is_object($object)) {
                $array = get_object_vars($object);
                if (is_array($array)) {
                    $result = $array['Message'];
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }
    /**
     * gets token key for all web service requests.
     *
     * @return string Indicates the token key
     */
    private function GetToken()
    {
        $postData = array(
            'UserApiKey' => $this->APIKey,
            'SecretKey' => $this->SecretKey,
            'System' => 'php_rest_v_1_2'
        );
        $postString = json_encode($postData);
        $ch = curl_init($this->getApiTokenUrl());
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, count(array($postString)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result);
        if (is_object($response)) {
            $resultVars = get_object_vars($response);
            if (is_array($resultVars)) {
                @$IsSuccessful = $resultVars['IsSuccessful'];
                if ($IsSuccessful == true) {
                    @$TokenKey = $resultVars['TokenKey'];
                    $resp = $TokenKey;
                } else {
                    $resp = false;
                }
            }
        }
        return $resp;
    }
    /**
     * executes the main method.
     *
     * @param postData[] $postData array of json data
     * @param string $url url
     * @param string $token token string
     * @return string Indicates the curl execute result
     */
    private function execute($postData, $url, $token)
    {
        $postString = json_encode($postData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-sms-ir-secure-token: ' . $token
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, count(array($postString)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
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
<div style="font-weight: bold;">ورود به حساب کاربری</div>
</div>


<br>

<p style="padding: 20px;">شمارهٔ موبایل خود را وارد کنید</p>

<p  style="padding: 0px 20px; font-size:13px; color:gray;">برای استفاده از امکانات دیوار، لطفاً شمارهٔ موبایل خود را وارد کنید. کد تأیید به این شماره پیامک خواهد شد.</p>



<?php if ($form_message !== '') {
    $msg_color = ($message_type === 'success') ? '#1a8917' : '#be3737';
    $msg_bg = ($message_type === 'success') ? '#e8f5e9' : '#fdecea';
    ?>
    <div style="margin: 0 20px 12px; padding: 10px 14px; border-radius: 8px; font-size: 14px;
                color: <?php echo $msg_color; ?>; background: <?php echo $msg_bg; ?>">
        <?php echo htmlspecialchars($form_message, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>

<form method="post" action="login.php" class="login-page">

<div class="main_search" style="box-shadow: none;" >
    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
        <i class="fa-solid fa-phone search1"></i>

        <input id="shomare" type="tel" value="<?php echo htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8'); ?>" name="mobile" placeholder="شماره موبایل" maxlength="11" minlength="11" pattern="09[0-9]{9}" inputmode="numeric" autocomplete="tel" required style="    border: none;
    width: 60%;
    height: 34px;
    margin: 0;
    vertical-align: top;
    margin-top: 3px;
    direction: ltr;
    outline: none;
    ">
        <label class="search_text2" style="direction: ltr;
    background: #f4f4f4;
    border-radius: 15px;
    padding: 0px
px
 8px;
    margin-left: 9px;
    border: none;
    font-size: 13px;
    color: #1e1e1e;">+۹۸</label>
    </div>



<br>

<?php

if ($step === 'code'){

    echo '

    <div class="search_divar" style="    background-color: #ffffff;
    border: 1px solid #eeeeee;">
        <i class="fa-solid fa-code search1"></i>

        <input name="code" placeholder=" کد ۶ رقمی" maxlength="6" minlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" required minlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" required style="    border: none;
    width: 60%;
    height: 34px;
    margin: 0;
    vertical-align: top;
    margin-top: 3px;
    direction: ltr;
    outline: none;
    ">
        <label class="search_text2" style="direction: ltr;
    border-radius: 15px;
    padding: 0px
px
 8px;
    margin-left: 9px;
    border: none;
    font-size: 13px;
    color: #1e1e1e;"> </label>
    </div>


';
}













?>










</div>


























    <br>
    <br>
    <br>
    <div class="divbottom2">


<button onclick="valid()" type="submit" tabindex="0" style="     background-color: #be3737;
    border: 1px solid transparent;
    border-radius: 4px;
    box-sizing: border-box;
    color: #fff;
    cursor: pointer;
    display: inline-flex;
    font-size: 1rem;
    font-weight: 500;
    height: 2.5rem;
    justify-content: center;
    min-width: 6rem;
    outline: none;
    overflow: hidden;
    padding: 0 16px;
    width: 100%;
    line-height: 38px; "><span ><?php echo $buttontext ; ?></span></button>

    </div>



</form>


<?php include("footer.php") ?>





<script>
let n= document.getElementById('shomare').value;

function valid() {
    if (n.length>11) {
       alert("شماره نمیتواند کمتر از 11 باشد")

    }

};



</script>





</body>
</html>