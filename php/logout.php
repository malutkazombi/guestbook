<?php
require_once __dir__.('/../php/functions.php');
require_login();

if (!validate_token(filter_input(INPUT_POST, 'token'))){
  header('Content-Type: text/plain; charset=UTF-8', true, 400);
  exit('トークンが無効です');
}
setcookie(session_name(), '', 1);
// セッションファイルの破棄
session_destroy();
// ログアウト完了後に /login.php に遷移
header('Location: /login.php');
?>
