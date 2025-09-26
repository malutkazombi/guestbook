<?php
require_once 'functions.php';

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
