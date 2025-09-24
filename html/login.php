<?php
require_once '/var/www/config/pepper.php';
require_once '/var/www/html/functions.php';
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
  <h1>Login Page</h1>
  <div class="group">
    <form id="signup" action="login.php" method="post">
      <h3>Login Id</h3>
      <input type="text" name="User_id" value="" rows="1" cols="10" wrap="hard"></input></br>
      <h3>Password</h3>
      <input type="password" name="password" value="" rows="1" cols="3" wrap="hard"></input>
      <br>
      <input class="password_btn" type="submit" value="Login" />
      <input type="hidden" name="token" value="<?= h(generate_token()) ?>">
    </form>
  </div>
</body>

</html>
<?php if (http_response_code() === 403): ?>
  <p style="color: red;">ユーザ名またはパスワードが違います</p>
<?php endif; ?>
