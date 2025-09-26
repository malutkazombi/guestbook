<?php
if (!isset($_POST['del'])) {
    header("Location:guestbook.php");
    exit;
}
$del = $_POST['del'];

try {
    $db = new PDO('mysql:host=mysql;dbname=test;charset=utf8', 'test', 'test');
    $sto = $db->prepare('DELETE FROM guest_book WHERE ID=:id');
    $sto->bindParam(':id', $del, PDO::PARAM_INT);
    if ($sto->execute()) {
        header("refresh:3;url=guestbook.php");
        $message = "データを削除しました。Guest_bookリストに戻ります。";
    } else {
        $message = "SQL文実行時にエラーが発生しました";
    }
    $db = null;
} catch (PDOException $e) {
    echo "delete.php 削除処理";
    die("<p>処理に失敗しました</p>");
}
