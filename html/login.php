<?php include_once(__DIR__.'/../php/logicLogin.php'); ?>
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
