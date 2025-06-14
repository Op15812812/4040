<?php
session_start();

// 檢查是否已登入為一般會員
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit;
}

$memberName = $_SESSION['member'];
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員中心 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fffaf0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #d2b48c;
            text-align: center;
        }
        h2 {
            color: #8b4513;
        }
        .logout-btn {
            background-color: #ff6347;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #e63946;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #20b2aa;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🎉 歡迎，<?= htmlspecialchars($memberName) ?>！</h2>
        <p>您已成功登入會員中心。</p>

        <a href="logout.php" class="logout-btn">登出</a>
        <a href="index.php" class="back-link">🦐 返回首頁</a>
    </div>
</body>
</html>
