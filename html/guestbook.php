<?php
require_once __dir__.('/../php/functions.php');

require_login();

session_start();
if (validate_token(filter_input(INPUT_POST, 'token')) && isset($_POST['data']) && (isset($_POST['message'])) && (isset($_SESSION['User_id']))) {
    try {
        $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if (isset($_POST['data']) && (isset($_POST['message'])) && (isset($_SESSION['User_id']))) {
            $data = filter_input(INPUT_POST, 'data');
            $message =  filter_input(INPUT_POST, 'message');
            $user_id = $_SESSION['User_id'];
            $date = date("Y-m-d H:i:s");

            $sto = $db->prepare('INSERT INTO guest_book(name,message,created_at,user_id) VALUES (:name,:message,:created_at,:user_id)');

            $sto->bindValue(':name', $data);
            $sto->bindValue(':message', $message);
            $sto->bindValue(':created_at', $date);
            $sto->bindValue(':user_id', $user_id);

            if ($sto->execute()) {
                header("Location: guestbook.php");
                exit;
            } else {
                print("<p>SQL文実行時にエラーが発生しました</p>");
            }
        }

        $sql = "SELECT * FROM guest_book WHERE User_id = :user_id ORDER BY created_at DESC";
        $user_id = $_SESSION['User_id'];

        $sto = $db->prepare($sql);
        $sto->bindValue(':user_id', $user_id);

        $sto->execute();
        $dataList = array();
        while ($row = $sto->fetch()) {
            array_push($dataList, ["name" => $row['name'], "message" => $row['message'], "user_id" => $row['user_id'], "created_at" => $row['created_at']]);
        }

        $db = null;
    } catch (PDOException $e) {
        die("<p>処理に失敗しました: " . $e->getMessage() . "</p>");
    }
}

if (validate_token(filter_input(INPUT_POST, 'token')) && isset($_POST['deleteAll'])) {
    $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM guest_book WHERE User_id = :user_id";

    $sto = $db->prepare($sql);
    $sto->execute();
}

try {
    $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
    if (!isset($_POST['getold'])) {
        $sql = "SELECT * FROM guest_book WHERE User_id = :user_id ORDER BY created_at DESC ";
    } else {
        $sql = "SELECT * FROM guest_book WHERE User_id = :user_id ORDER BY created_at ASC ";
    }
    $user_id = $_SESSION['User_id'];

    $sto = $db->prepare($sql);
    $sto->bindValue(':user_id', $user_id);

    $sto->execute();

    $dataList = array();
    while ($row = $sto->fetch()) {
        array_push($dataList, ["id" => $row['ID'], "name" => $row['name'], "message" => $row['message'], "user_id" => $row['user_id'], "created_at" => $row['created_at']]);
    }

    $db = NULL;
} catch (PDOException $e) {
    echo "todo.php 表示処理";
    die('処理に失敗しました');
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
