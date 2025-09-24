<?php
function require_login(){
  session_start();

  if(!isset($_SESSION['User_id'])){
    header('Location:login.php');
    exit;
  }
}

function generate_token()
{
    // セッションIDからハッシュを生成
    return hash('sha256', session_id());
}

function validate_token($token)
{
    // 送信されてきた$tokenがこちらで生成したハッシュと一致するか検証
    return $token === generate_token();
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>
