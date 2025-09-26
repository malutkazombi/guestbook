<?php
require_once '/var/www/config/pepper.php';
require_once __DIR__.('/../php/functions.php');
session_start();
if (validate_token(filter_input(INPUT_POST, 'token')) && isset($_POST['User_id']) && (isset($_POST['password']))) {
  $user_id = filter_input(INPUT_POST, 'User_id');
  $password =  filter_input(INPUT_POST, 'password');
  $pwd_peppered = hash_hmac("sha256", $password, $pepper);

  try {
    $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $db->prepare('SELECT password,User_name FROM Users where user_id = :user_id');
    $stmt->bindValue(':user_id', $user_id);
    $stmt->execute();

    if ($row = $stmt->fetch()) {
      $hashedpassword = $row['password'];
      $user_name = $row['User_name'];
    } else {
      $hashedpassword = '$2y$10$abcdefghijklmnopqrstuv';
      $user_name = '';
    }

    if (password_verify($pwd_peppered, $hashedpassword)) {
      $_SESSION['User_id'] = $user_id;
      $_SESSION['User_name'] = $user_name;
      header("Location: guestbook.php");
      exit;
    } else {
      http_response_code(403);
    }
  } catch (PDOException $e) {
    die("<p>処理に失敗しました: " . $e->getMessage() . "</p>");
  }
}
?>
