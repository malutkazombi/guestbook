<?php
require_once '/var/www/config/pepper.php';
require_once '/var/www/html/functions.php';
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
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="guestbook.css">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>todo</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
  <h1>Sign Up Page</h1>
  <div class="group">
    <form id="signup" action="signup.php" method="post">
      <h3>Login Id</h3>
      <input type="text" name="User_id" value="" rows="1" cols="10" wrap="hard"></input></br>
      <h3>Login name</h3>
      <input type="text" name="User_name" value="" rows="3" cols="20" wrap="hard"></input>
      <h3>Password</h3>
      <input type="text" name="password" value="" rows="1" cols="3" wrap="hard"></input>
      <br>
      <input class="password_btn" type="submit" value="Submit" />
      <input type="hidden" name="token" value="<?=h(generate_token())?>">
    </form>
  </div>
</body>

</html>
