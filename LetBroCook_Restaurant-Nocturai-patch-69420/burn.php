<?php
session_start();

// 僅限 admin 執行
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("❌ 資料庫連線失敗：" . mysqli_connect_error());
}

$success = true;
$messages = [];

// 關閉外鍵檢查
mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 0");

// 執行刪除與重置 AUTO_INCREMENT
$queries = [
    "TRUNCATE TABLE 訂單明細",
    "TRUNCATE TABLE 訂單",
    "TRUNCATE TABLE 會員"// 假設資料表名稱為「會員」
];

foreach ($queries as $sql) {
    if (!mysqli_query($link, $sql)) {
        $messages[] = "⚠️ 執行失敗：$sql - " . mysqli_error($link);
        $success = false;
    }
}

// 恢復外鍵檢查
mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 1");

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>🔥 全部清除</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            text-align: center;
            padding: 80px 20px;
        }
        .burn-box {
            background-color: #fff0f0;
            padding: 40px;
            max-width: 600px;
            margin: auto;
            border-radius: 16px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: <?= $success ? "'#cc0000'" : "'#ff0000'" ?>;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #ff6b6b;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #ff4d4d;
        }
        ul {
            text-align: left;
            margin-top: 20px;
            color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="burn-box">
        <?php if ($success): ?>
            <h2>🔥 所有餐點/訂單/會員資料已清空，ID 重置成功！</h2>
        <?php else: ?>
            <h2>❌ 清除失敗</h2>
            <ul>
                <?php foreach ($messages as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="admin.php" class="back-btn">⬅ 返回管理後台</a>
    </div>
</body>
</html>
