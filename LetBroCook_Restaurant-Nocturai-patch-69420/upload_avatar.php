<?php
session_start();
if (!isset($_SESSION['member'])) {
    header("Location: member_login.php");
    exit;
}

$member_username = $_SESSION['member'];

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $targetPath = "uploads/" . $member_username . ".jpg"; // 強制存成 jpg（也可以用 $ext）

    // 儲存圖片
    move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath);
}

// 回到會員中心
header("Location: member_center.php");
exit;
