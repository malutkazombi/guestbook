<?php require_once(__DIR__.'/../php/logicUpdate.php');?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="guestbook.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベース更新</title>
</head>

<body>
    <?php if (isset($errorMsg)) echo $errorMsg; ?>
    <?php foreach ($dataList as $data) : ?>
        <p>Id number :<?php echo $data['id']; ?> の編集画面</p>
        <form id="decision" action="update.php" method="post">
            <textarea class="form" name="newdata" value="" rows="3" cols="20" wrap="hard"><?php echo $data['name']; ?></textarea>
            <br>
            <textarea class="form" name="newmessage" value="" rows="3" cols="20" wrap="hard"><?php echo $data['message']; ?></textarea>
            <input class="update-btn submit-btn" type="submit" value="更新" />
            <input type='hidden' name='decision' value='<?php echo $update; ?>' />
        </form>
    <?php endforeach ?>
</body>

</html>
