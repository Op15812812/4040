<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

$where_clause = "";
$params = [];
$param_types = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['order_id'])) {
        $where_clause = "WHERE 訂單編號 = ?";
        $params[] = $_POST['order_id'];
        $param_types = "i";
    } elseif (!empty($_POST['member_username'])) {
        $where_clause = "WHERE 會員帳號 LIKE ?";
        $params[] = "%" . $_POST['member_username'] . "%";
        $param_types = "s";
    }
}

$sql = "SELECT * FROM 訂單 $where_clause ORDER BY 訂單編號 DESC";
$stmt = mysqli_prepare($link, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>讓兄弟煮 - 訂單查詢</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-box">
        <h1>🔍 訂單查詢</h1>

        <form method="POST">
            <label>訂單編號：</label>
            <input type="number" name="order_id">
            <br>
            <label>會員帳號：</label>
            <input type="text" name="member_username">
            <br>
            <button type="submit" class="admin-button">查詢</button>
            <a href="admin.php" class="admin-button">🦐 返回管理後台</a>
        </form>

        <h2>查詢結果：</h2>
        <table border="1" style="margin: auto;">
            <tr>
                <th>訂單編號</th>
                <th>會員帳號</th>
                <th>訂單時間</th>
                <th>總金額</th>
                <th>操作</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['訂單編號']) ?></td>
                <td><?= htmlspecialchars($row['會員帳號']) ?></td>
                <td><?= htmlspecialchars($row['下單時間']) ?></td>
                <td><?= htmlspecialchars($row['總金額']) ?> 元</td>
                <td>
    <a href="delete_order.php?id=<?= $row['訂單編號'] ?>" 
       onclick="return confirm('確定要刪除此訂單嗎？')">🗑 刪除</a>
</td>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($link);
?>
