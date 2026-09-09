<?php
include("footer.php");
require("db.php ");
// require("../sms_api.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');

$mobile="";
$code="";


$buttontext='تأیید';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mobile']) && !isset($_POST['code'])){

    $mobile = preg_replace('/[^0-9]/', '', (string)$_POST['mobile']);
    if (!preg_match('/^09[0-9]{9}$/', $mobile)) { $mobile = ''; }

    $code=random_int(100000,999999);

    $stmt = mysqli_prepare($db, 'INSERT INTO smscode (mobile, smscode) VALUES (?, ?)');
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
    echo 'Error UltraFastSend : '.$e->getMessage();
    }



    $sms_status = $sms_status ?? '';
    $buttontext='ورود';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mobile'], $_POST['code']) && preg_match('/^[0-9]{6}$/', $_POST['code'])){
    $mobile = preg_replace('/[^0-9]/', '', (string)$_POST['mobile']);
    $code = (string)$_POST['code'];
    $code_org='';

$stmt = mysqli_prepare($db, 'SELECT smscode FROM smscode WHERE mobile = ? ORDER BY id DESC LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $mobile); mysqli_stmt_execute($stmt); $sql2 = mysqli_stmt_get_result($stmt);

if ($row=mysqli_fetch_assoc($sql2)){
    $code_org=$row['smscode'];
}

// echo $code_org;


if ($code_org == $code){
    $stmt = mysqli_prepare($db, 'INSERT INTO user (mobile, password) VALUES (?, ?)');
    $passwordHash = password_hash($code, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, 'ss', $mobile, $passwordHash); mysqli_stmt_execute($stmt);

echo 'کد صحیح است';
echo '<meta http-equiv="refresh" content="1; url=agahi.php">';

}else{
    echo 'کد اشتباه است';

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



<form method="post" action="login.php" class="login-page" novalidate>

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

if (isset($_POST['mobile'])){

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