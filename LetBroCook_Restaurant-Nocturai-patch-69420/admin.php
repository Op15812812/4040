<?php
session_start();

// 如果沒有登入，轉回登入頁
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <link rel="stylesheet" href="admin.css">
    <meta charset="UTF-8">
    <title>讓兄弟煮 - 管理後台</title>
    <link rel="stylesheet" href="admin.css">

</head>
<body>
    <div class="admin-box">
        <h1>👨‍🍳 歡迎回來，主廚！</h1>
        <p>這是後台管理頁面。🔥🔥🔥</p>
<div class="admin-menu">
    <a href="orders_list.php" class="admin-button">📦 管理訂單</a>
    <a href="orders_search.php" class="admin-button">🔍 訂單查詢</a>
    <a href="menu_manage.php" class="admin-button">📋 編輯菜單</a>
    <a href="users_manage.php" class="admin-button">👥 會員管理</a>
    <a href="burn.php" class="admin-button">🔥🔥🔥💀🔥🔥🔥</a>
    <a href="index.php" class="admin-button">🏠 返回首頁</a> 
</div>



        <!-- 可以在這裡擴充功能選單，例如：商品管理、訂單管理等 -->

        <form method="POST" action="logout.php">
            <button type="submit" class="logout-button">登出</button>
        </form>
    </div>
</body>
</html>
