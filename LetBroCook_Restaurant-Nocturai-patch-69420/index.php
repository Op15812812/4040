<?php
session_start();
$member_logged_in = isset($_SESSION['member']);
$admin_logged_in = isset($_SESSION['admin']);
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>讓兄弟煮 - 首頁</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fffdf5;
            margin: 0;
            padding: 50px;
            text-align: center;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 14px;
            padding: 20px;
        }

        .top-right {
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .login-button, .register-button {
            text-decoration: none;
            font-size: 16px;
            padding: 8px 12px;
            background-color: #f1c40f;
            color: #000;
            border-radius: 5px;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .login-button:hover, .register-button:hover {
            background-color: #e6b800;
        }

        .menu {
            text-align: center;
            margin-top: 100px;
        }

        .button {
            display: inline-block;
            margin: 15px;
            padding: 14px 25px;
            font-size: 18px;
            background-color: #ffae42;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ff8c00;
        }

        .admin-block {
            margin-top: 60px;
        }

        .admin-image {
            width: 150px;
            height: auto;
            cursor: pointer;
            transition: transform 0.3s, opacity 0.3s;
        }

        .admin-image:hover {
            transform: scale(1.1);
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="top-right">
    <?php if ($admin_logged_in): ?>
        <a href="admin.php" class="login-button">🛠️ 管理中心</a>
        <a href="logout.php" class="login-button">🚪 登出</a>
    <?php elseif ($member_logged_in): ?>
        <a href="member_center.php" class="login-button">👤 會員中心</a>
        <a href="logout.php" class="login-button">🚪 登出</a>
    <?php else: ?>
        <a href="member_login.php" class="login-button">🔐 登入</a>
        <a href="member_register.php" class="login-button">📝 註冊</a>
    <?php endif; ?>
</div>


    <h1 style="text-align:center;">🍽 讓兄弟煮🗣️🔥🔥🔥 - 會員制餐廳</h1>

    <div class="menu">
        <a href="menu.php" class="button">📋 查看菜單</a>
        <a href="order.php" class="button">🛒 開始點餐</a>
        <?php if ($admin_logged_in): ?>
            <a href="orders_list.php" class="button">📦 訂單紀錄</a>
        <?php endif; ?>
    </div>


    <div class="admin-block">
    <?php if ($admin_logged_in): ?>
        <img src="cnm.png" alt="管理員介面" class="admin-image">
    <?php else: ?>
         <a href="login.php">
        <img src="let_him_cook.jpg" alt="一般登入" class="admin-image">
        </a>
    <?php endif; ?>
</div>
<!-- 加入音效 -->
<audio id="callSound" src="call.ogg" preload="auto"></audio>

<footer>
    <h3 onclick="playCallSound()" style="cursor: pointer;">
        ☎️🤙🤙🤙: 19-19810889464
    </h3>
</footer>

<script>
function playCallSound() {
    const audio = document.getElementById('callSound');
    audio.play();
}
</script>

</body>
</html>
