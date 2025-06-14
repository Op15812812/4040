<?php
session_start();

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("❌ 資料庫連線失敗：" . mysqli_connect_error());
}

$sql = "SELECT * FROM 訂單 ORDER BY 下單時間 DESC";
$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>📦 訂單紀錄 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            margin: 0;
            padding: 40px 20px;
        }
        h2 {
            text-align: center;
            color: #a0522d;
            margin-bottom: 30px;
            font-size: 2em;
        }
        .order-card {
            background-color: #fffaf0;
            max-width: 960px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1.5px solid #ffcc99;
            padding: 12px;
            text-align: center;
            font-size: 1em;
        }
        th {
            background-color: #ffe4c4;
            color: #5c4033;
        }
        td {
            background-color: #fffdf9;
        }
        .back-button {
            display: inline-block;
            margin-top: 30px;
            background-color: #ffb347;
            color: white;
            padding: 12px 25px;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #ff8c00;
        }
        .center {
            text-align: center;
        }
        .delete-link {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
    function confirmDelete(orderId) {
        if (confirm("確定要刪除訂單編號 " + orderId + " 嗎？")) {
            window.location.href = "reset.php?id=" + orderId;
        }
    }
    </script>
</head>
<body>

    <h2>📦 歷史訂單紀錄</h2>
    <div class="order-card">
        <table>
            <tr>
                <th>訂單編號</th>
                <th>訂單內容</th>
                <th>總金額</th>
                <th>下單時間</th>
                <th>操作</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['訂單編號']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['訂單內容'])) ?></td>
                    <td><?= htmlspecialchars($row['總金額']) ?> 元</td>
                    <td><?= htmlspecialchars($row['下單時間']) ?></td>
                    <td>
                        <a href="javascript:void(0);" class="delete-link" onclick="confirmDelete(<?= $row['訂單編號'] ?>)">🗑 刪除</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="center">
            <a href="admin.php" class="back-button">🦐 返回管理後臺</a>
        </div>
    </div>

<?php
mysqli_free_result($result);
mysqli_close($link);
?>

</body>
</html>
