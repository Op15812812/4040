<?php
// 設定資料庫連線
$link = @mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("<div class='error'>❌ 資料庫連線失敗：" . mysqli_connect_error() . "</div>");
}

$success = false;
$messages = [];

$order_id = $_GET['id'] ?? '';

if (!ctype_digit($order_id)) {
    $messages[] = "⚠️ 請提供有效的訂單編號。";
} else {
    // 關閉外鍵檢查（避免外鍵限制錯誤）
    mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 0");

    // 刪除指定訂單的訂單明細與訂單本身
    $sqls = [
        "DELETE FROM 訂單明細 WHERE 訂單編號 = $order_id;",
        "DELETE FROM 訂單 WHERE 訂單編號 = $order_id;"
    ];

    $success = true;

    foreach ($sqls as $sql) {
        if (!mysqli_query($link, $sql)) {
            $messages[] = "執行失敗：$sql";
            $success = false;
        }
    }

    // 重啟外鍵檢查
    mysqli_query($link, "SET FOREIGN_KEY_CHECKS = 1");
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>刪除單筆訂單</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            padding: 60px 20px;
            text-align: center;
        }
        .message-box {
            background-color: #fffaf0;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        }
        h2 {
            color: <?= $success ? '#228B22' : '#cc0000' ?>;
        }
        .error {
            color: #cc0000;
            font-weight: bold;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: #ffb347;
            color: white;
            font-size: 1.1em;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #ff8c00;
        }
        ul {
            text-align: left;
            margin-top: 20px;
            color: #cc0000;
        }
    </style>
</head>
<body>

<div class="message-box">
    <?php if ($success): ?>
        <h2>✅ 已成功刪除訂單（編號：<?= htmlspecialchars($order_id) ?>）</h2>
    <?php else: ?>
        <h2>❌ 刪除失敗</h2>
        <ul>
            <?php foreach ($messages as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="orders_list.php" class="back-btn">📦 回訂單紀錄</a>
</div>

</body>
</html>
