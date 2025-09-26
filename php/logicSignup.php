<?php
require_once '/var/www/config/pepper.php';
require_once __dir__.('/../php/functions.php');
if (isset($_POST['User_id']) && (isset($_POST['User_name'])) && (isset($_POST['password']))) {
  try {
    $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (validate_token(filter_input(INPUT_POST, 'token')) && isset($_POST['User_id']) && (isset($_POST['User_name'])) && (isset($_POST['password']))) {
      $user_id = filter_input(INPUT_POST, 'User_id');
      $user_name =  filter_input(INPUT_POST, 'User_name');
      $password =  filter_input(INPUT_POST, 'password');

      $pwd_peppered = hash_hmac("sha256", $password, $pepper);
      $hashedPassword = password_hash($pwd_peppered, PASSWORD_ARGON2ID);

      $sto = $db->prepare('INSERT INTO Users(user_id,user_name,password) VALUES (:user_id,:user_name,:password)');

      $sto->bindValue(':user_id', $user_id);
      $sto->bindValue(':user_name', $user_name);
      $sto->bindValue(':password', $hashedPassword);

      if ($sto->execute()) {
        header("Location: login.php");
        exit;
      } else {
        print("<p>SQL文実行時にエラーが発生しました</p>");
      }
    }
  } catch (PDOException $e) {
    die("<p>処理に失敗しました: " . $e->getMessage() . "</p>");
  }
}
?>
