<?php
session_start();
if (!isset($_SESSION['member']) && !isset($_SESSION['admin'])) {
    header("Location: member_login.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>讓兄弟煮 - 開始點餐</title>
    <link rel="stylesheet" href="order.css">

    <!-- 播放音效 -->
    <audio id="orderSound" src="max.ogg" preload="auto"></audio>
    <script>
    function submitAfterSound(event) {
        event.preventDefault(); // 阻止表單直接送出

        const audio = document.getElementById('orderSound');
        audio.play();

        audio.onended = function () {
            document.getElementById('orderForm').submit();
        };
    }
    </script>
</head>
<body>

<h2>🛒 選擇您要的餐點</h2>

<form id="orderForm" method="post" action="submit_order.php" onsubmit="submitAfterSound(event)">
    <table>
        <tr>
            <th>餐點編號</th>
            <th>餐點名稱</th>
            <th>分類編號</th>
            <th>價格</th>
            <th>數量</th>
        </tr>

<?php

// 資料庫連線
$link = @mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

// 查詢餐點
$sql = "SELECT * FROM 餐點";
$result = mysqli_query($link, $sql);
if (!$result) {
    die("查詢失敗：" . mysqli_error($link));
}

// 顯示每一列
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['餐點編號']) . "</td>";
    echo "<td>" . htmlspecialchars($row['餐點名稱']) . "</td>";
    echo "<td>" . htmlspecialchars($row['分類名稱']) . "</td>";
    echo "<td>" . htmlspecialchars($row['價格']) . " 元</td>";
    echo "<td><input type='number' name='quantity[" . $row['餐點編號'] . "]' min='0' value='0'></td>";
    echo "</tr>";
}

mysqli_close($link);
?>

    </table>    
<p>👤 當前身份：
<?php
    if (isset($_SESSION['admin'])) {
        echo "管理員 (" . htmlspecialchars($_SESSION['admin']) . ")";
    } elseif (isset($_SESSION['member'])) {
        echo "會員 (" . htmlspecialchars($_SESSION['member']) . ")";
    }
?>
</p>

    <div style="text-align:center;">
        <button type="submit">送出訂單</button>
    </div>
    <div class="container">
        <div class="menu">
            <a href="index.php" class="button">🦐 返回首頁</a>
        </div>
    </div>
</form>

</body>
</html>
