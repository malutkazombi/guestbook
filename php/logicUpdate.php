<?php

if (!isset($_POST['update']) && (!isset($_POST['newdata'])) && !isset($_POST['newmessage'])) {
    header("Location:guestbook.php");
    exit;
}

if (isset($_POST['newdata']) && (isset($_POST['newmessage']))) {
    $newdata = $_POST['newdata'];
    $newmessage = $_POST['newmessage'];
    $decision = $_POST['decision'];

    //更新用
    try {
        $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
        $sto = $db->prepare('UPDATE guest_book SET name=:newdata, message=:newmessage WHERE id=:id');
        $sto->bindParam(':id', $decision, PDO::PARAM_INT);
        $sto->bindParam(':newdata', $newdata, PDO::PARAM_STR);
        $sto->bindParam(':newmessage', $newmessage, PDO::PARAM_STR);
        if ($sto->execute()) {
            header("Location:new.php");
            exit;
        } else {
            $errorMsg = "<p>SQL文実行時にエラーが発生しました</p>";
        }
        $db = null;
    } catch (PDOException $e) {
        echo "update.php 更新処理";
        die("<p>処理に失敗しました</p>");
    }
}

$dataList = [];
$update = '';
if (isset($_POST['update'])) {
    //更新するデータをidをもとに表示する
    $update = $_POST['update'];
    try {
        $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
        $sto = $db->prepare('SELECT name, message, ID FROM guest_book WHERE id=:id');
        $sto->bindParam(':id', $update, PDO::PARAM_INT);
        $sto->execute();


        while ($row = $sto->fetch()) {
            $dataList[] = [
                "name" => $row['name'],
                "message" => $row['message'],
                "id" => $row['ID']
            ];
        }

        $db = NULL;
    } catch (PDOException $e) {
        echo "update.php 表示処理";
        die('処理に失敗しました');
    }
}
