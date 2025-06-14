<?php
session_start();

// 確認是否為管理員登入
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("❌ 資料庫連線失敗：" . mysqli_connect_error());
}

// 處理刪除會員
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = mysqli_prepare($link, "DELETE FROM 會員 WHERE 會員編號 = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: member_manage.php");
    exit;
}

// 取得所有會員資料
$result = mysqli_query($link, "SELECT * FROM 會員 ORDER BY 註冊時間 DESC");
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員管理 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #8b4513;
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
    </style>
</head>
<body>
    <h2>👥 會員管理</h2>

    <table>
        <tr>
            <th>會員編號</th>
            <th>使用者帳號</th>
            <th>姓名</th>
            <th>電話</th>
            <th>電子郵件</th>
            <th>註冊時間</th>
            <th>操作</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['會員編號']) ?></td>
            <td><?= htmlspecialchars($row['使用者帳號']) ?></td>
            <td><?= htmlspecialchars($row['姓名']) ?></td>
            <td><?= htmlspecialchars($row['電話']) ?></td>
            <td><?= htmlspecialchars($row['電子郵件']) ?></td>
            <td><?= htmlspecialchars($row['註冊時間']) ?></td>
            <td>
                <a href="?delete_id=<?= $row['會員編號'] ?>" onclick="return confirm('確定要刪除此會員？');">
                    <button class="delete-btn">刪除</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="admin.php" class="back-btn"> 返回後台首頁</a>
</body>
</html>
<?php
mysqli_free_result($result);
mysqli_close($link);
?>
