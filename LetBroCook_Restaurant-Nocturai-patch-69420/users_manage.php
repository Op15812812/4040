<?php
session_start();

// 只有管理員可以進入
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

// 處理刪除會員
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = mysqli_prepare($link, "DELETE FROM 會員 WHERE 會員編號 = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 刪除完刷新頁面
    header("Location: manage_members.php");
    exit;
}

// 查詢會員資料
$result = mysqli_query($link, "SELECT * FROM 會員 ORDER BY 會員編號 ASC");

?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員管理 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fffdf5;
            padding: 40px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            margin: auto;
            width: 90%;
            background-color: #fff;
            box-shadow: 0 0 10px #d2b48c;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
        }

        th {
            background-color: #f4a460;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .delete-button {
            color: white;
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .back-link {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    font-size: 18px;
    background-color: #ffae42;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s;
    font-weight: bold;
}

.back-link:hover {
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
            <a class="delete-button" href="manage_members.php?delete_id=<?= $row['會員編號'] ?>" onclick="return confirm('確定要刪除該會員？');">刪除</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="admin.php" class="back-link">🦐 返回管理頁面</a>

</body>
</html>
<?php
mysqli_close($link);
?>
