<?php


require("db.php");
date_default_timezone_set("Asia/Tehran");
mysqli_set_charset($db, 'utf8');



header('Content-Type: text/plain; charset=utf-8');
$chat = trim((string)($_POST['chat'] ?? ''));
$from_user_id = (string)($_POST['user_id'] ?? '');
$to_user_id = '1';
if (!in_array($from_user_id, ['1', '19'], true) || $chat === '' || mb_strlen($chat, 'UTF-8') > 200) { http_response_code(422); exit('پیام نامعتبر است'); }


if ($from_user_id=='1'){
    $to_user_id='19';
}else{
    $to_user_id='1';
}

$stmt = mysqli_prepare($db, 'INSERT INTO chat (chattext, from_user_id, to_user_id) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'sss', $chat, $from_user_id, $to_user_id);
if (!mysqli_stmt_execute($stmt)) { http_response_code(500); exit('خطا در ارسال پیام'); }
echo htmlspecialchars($chat, ENT_QUOTES, 'UTF-8');




?>