<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>讓兄弟煮 - 菜單</title>
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
        .menu-card {
            background-color: #fffaf0;
            max-width: 900px;
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
            background-color: #fffaf5;
        }
        a.button {
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
        a.button:hover {
            background-color: #ff8c00;
        }
        .menu-footer {
            text-align: center;
        }
    </style>
</head>
<body>

<?php
// 建立資料庫連線
$link = @mysqli_connect('localhost', 'root', '', '讓兄弟煮');

// 檢查連線是否成功
if (!$link) {
    die("<p style='text-align:center;color:red;'>❌ 無法連接資料庫：" . mysqli_connect_error() . "</p>");
}

// 查詢餐點資料
$sql = "SELECT * FROM 餐點";
$result = mysqli_query($link, $sql);

if (!$result) {
    die("<p style='text-align:center;color:red;'>❌ 查詢失敗：" . mysqli_error($link) . "</p>");
}

// 顯示菜單
echo "<h2>🍽 讓兄弟煮 - 菜單</h2>";
echo "<div class='menu-card'>";
echo "<table>";
echo "<tr><th>餐點編號</th><th>餐點名稱</th><th>分類名稱</th><th>價格</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['餐點編號']) . "</td>";
    echo "<td>" . htmlspecialchars($row['餐點名稱']) . "</td>";
    echo "<td>" . htmlspecialchars($row['分類名稱']) . "</td>";
    echo "<td>" . intval($row['價格']) . " 元</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";
echo "<div class='menu-footer'><a href='index.php' class='button'>🦐 返回首頁</a></div>";

// 清理與關閉
mysqli_free_result($result);
mysqli_close($link);
?>

</body>
</html>
