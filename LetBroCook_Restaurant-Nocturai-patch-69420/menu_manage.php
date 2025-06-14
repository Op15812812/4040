<?php
session_start();

// 僅限已登入的管理員使用
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("❌ 資料庫連線失敗：" . mysqli_connect_error());
}

$insert_message = "";

// ➕ 新增餐點
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    $name = trim($_POST['餐點名稱']);
    $category = trim($_POST['分類名稱']);
    $price = intval($_POST['價格']);

    if (!empty($name) && !empty($category) && $price > 0) {
        $stmt = mysqli_prepare($link, "INSERT INTO 餐點 (餐點名稱, 分類名稱, 價格) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssd", $name, $category, $price);
        if (mysqli_stmt_execute($stmt)) {
            $new_id = mysqli_insert_id($link);
            $insert_message = "✅ 餐點新增成功，餐點編號為：$new_id";
        } else {
            $insert_message = "❌ 餐點新增失敗：" . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    } else {
        $insert_message = "⚠️ 請正確填寫所有欄位（價格需大於 0）";
    }
}

// 🗑 刪除餐點
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = mysqli_prepare($link, "DELETE FROM 餐點 WHERE 餐點編號 = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: menu_manage.php");
    exit;
}

// 查詢所有餐點
$result = mysqli_query($link, "SELECT * FROM 餐點 ORDER BY 餐點編號 ASC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>餐點管理 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #a0522d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ffcc99;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #ffe4c4;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .add-form {
            margin-top: 40px;
            text-align: center;
        }
        .add-form input {
            padding: 8px;
            margin: 5px;
        }
        .add-btn {
            background-color: #2ecc71;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .add-btn:hover {
            background-color: #27ae60;
        }
        .back-btn {
            display: block;
            margin: 30px auto 0;
            width: 160px;
            text-align: center;
            background-color: #ffa500;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
        }
        .back-btn:hover {
            background-color: #ff8c00;
        }
        .msg {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1em;
            color: #006400;
        }
        .msg.error {
            color: #cc0000;
        }
    </style>
</head>
<body>
    <h2>🍜 餐點管理</h2>

    <?php if (!empty($insert_message)): ?>
        <div class="msg <?= strpos($insert_message, '❌') !== false || strpos($insert_message, '⚠️') !== false ? 'error' : '' ?>">
            <?= htmlspecialchars($insert_message) ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>餐點編號</th>
            <th>餐點名稱</th>
            <th>分類名稱</th>
            <th>價格</th>
            <th>操作</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['餐點編號']) ?></td>
            <td><?= htmlspecialchars($row['餐點名稱']) ?></td>
            <td><?= htmlspecialchars($row['分類名稱']) ?></td>
            <td><?= intval($row['價格']) ?> 元</td>
            <td>
                <a href="?delete_id=<?= $row['餐點編號'] ?>" onclick="return confirm('確定要刪除這筆餐點？');">
                    <button class="delete-btn">刪除</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="add-form">
        <h3>➕ 新增餐點</h3>
        <form method="POST">
            <input type="text" name="餐點名稱" placeholder="餐點名稱" required>
            <input type="text" name="分類名稱" placeholder="分類名稱" required>
            <input type="number" step="0.01" name="價格" placeholder="價格" required>
            <input type="submit" name="add" value="新增餐點" class="add-btn">
        </form>
    </div>

    <a href="admin.php" class="back-btn">🦐 返回後台首頁</a>
</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($link);
?>
