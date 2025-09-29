<?php
include_once __DIR__.'/../php/logicGuestbook.php'?>

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
    <h1>MyGuestBook</h1>
    <p>Welcome <? echo $_SESSION['User_name'] ?> さん</p>

    <form id="add" action="guestbook.php" method="post">
        <h3>Please write theme</h3>
        <textarea class="form" name="data" value="" rows="1" cols="10" wrap="hard"></textarea></br>
        <h3>Please write your message</h3>
        <textarea class="form" name="message" value="" rows="3" cols="20" wrap="hard"></textarea>
        <input class="submit-btn add-btn" type="submit" value="Send" />
        <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
        <br>
    </form>

    <form id='getold' action='guestbook.php' method='post'>
        <input class="edit-btn" type='submit' value='Get oldest GuestBook' />
        <input type='hidden' name='getold' value="Send" />
        <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
    </form>

    <form id='deleteAll' action='guestbook.php' method='post'>
        <input class="edit-btn" type='submit' value='Delete All' />
        <input type='hidden' name='deleteAll' value="Send" />
        <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
    </form>

    <form id='logOut' action='/../php/logout.php' method='post'>
        <input class="edit-btn" type='submit' value='Logout' />
        <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
    </form>

    <?php foreach ($dataList as $data) : ?>
        <div class="group">
            <b>User ID</b>
            <p><?php echo h($_SESSION['User_id']); ?></p>
            <b>Name for your memo </b>
            <p><?php echo $data['name']; ?></p>
            <b>Message </b>
            <p><?php echo $data['message']; ?></p>
            <b>Created time</b>
            <p><?php echo $data['created_at']; ?></p>
            <form id='del' action='del.php' method='post'>
                <input class="del-btn" type='submit' value='削除' />
                <input type='hidden' name='del' value='<?php echo $data['id']; ?>' />
                <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
            </form>
            <form id='update' action='update.php' method='post'>
                <input class="edit-btn" type='submit' value='編集' />
                <input type='hidden' name='update' value='<?php echo $data['id']; ?>' />
                <input type='hidden' name='token' value="<?php echo h(generate_token()); ?>">
            </form>
        </div>
        <br>
    <?php endforeach ?>
</body>

</html>
