<?
if (!isset($_POST['search'])) {
    header("Location:guestbook.php");
    exit;
} ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for...</title>
</head>

<body>
    <form id="add" action="search.php" method="post">
        <h3>Search for name</h3>
        <textarea class="form" name="data" value="" rows="1" cols="10" wrap="hard"></textarea></br>
        <h3>Search for message</h3>
        <textarea class="form" name="message" value="" rows="3" cols="20" wrap="hard"></textarea>
        <input class="submit-btn add-btn" type="submit" value="searchMess" />
        <br>
    </form>
    <?php foreach ($dataList as $data) : ?>
        <div class="group">
            <b>ID</b>
            <p><?php echo $data['id']; ?></p>
            <b>Name </b>
            <p><?php echo $data['name']; ?></p>
            <b>Message </b>
            <p><?php echo $data['message']; ?></p>
            <b>Created time</b>
            <p><?php echo $data['created_at']; ?></p>
            <form id='del' action='del.php' method='post'>
                <input class="del-btn" type='submit' value='削除' />
                <input type='hidden' name='del' value='<?php echo $data['id']; ?>' />
            </form>
            <form id='update' action='update.php' method='post'>
                <input class="edit-btn" type='submit' value='編集' />
                <input type='hidden' name='update' value='<?php echo $data['id']; ?>' />
            </form>
        </div>
        <br>
    <?php endforeach ?>
</body>

</html>
