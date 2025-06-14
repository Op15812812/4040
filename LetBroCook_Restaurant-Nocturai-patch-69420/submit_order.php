<?php
// submit_order.php

session_start();
if (!isset($_SESSION['member']) && !isset($_SESSION['admin'])) {
    header("Location: member_login.php");
    exit;
}

if (isset($_SESSION['member'])) {
    $userAccount = $_SESSION['member'];
} else {
    $userAccount = $_SESSION['admin'];
}

$link = @mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("連線失敗：" . mysqli_connect_error());
}

$quantities = $_POST['quantity'] ?? [];

$totalItems = 0;
foreach ($quantities as $qty) {
    if ((int)$qty > 0) $totalItems += (int)$qty;
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>訂單結果</title>

    <style>
    body {
        font-family: "微軟正黑體", sans-serif;
        text-align: center;
        background-color: #fffdf5;
        padding: 40px;
        margin: 0;
    }
    .order-container {
        max-width: 800px;
        margin: auto;
        background-color: #fffaf0;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .order-success-title {
        color: #228B22;
        font-size: 2em;
        margin-bottom: 10px;
    }
    .order-id {
        font-size: 1.2em;
        color: #333;
        margin-bottom: 30px;
    }
    .order-title {
        font-size: 1.5em;
        color: #a0522d;
        margin: 20px 0;
    }
    .order-table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 100%;
        background-color: #fff;
        font-size: 1.1em;
    }
    .order-table th {
        background-color: #ffb347;
        color: white;
        padding: 12px;
        border: 1px solid #ddd;
    }
    .order-table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    .order-total {
        background-color: #ffe4b5;
        font-weight: bold;
    }
    .order-success {
        color: #228B22;
        font-size: 1.1em;
        margin-top: 20px;
        font-weight: bold;
    }
    .back-home {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 20px;
        background-color: #ffb347;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .back-home:hover {
        background-color: #ff8c00;
    }
</style>


</head>
<body>
    
<div class="order-container">
<?php
if ($totalItems == 0) {
    echo "<h2>請至少選擇一項餐點！</h2>";
    echo "<a class='back-home' href='order.php'>回到點餐頁面</a>";
    exit;
}

// 建立訂單（包含會員帳號）
$stmt_order = mysqli_prepare($link, "INSERT INTO 訂單 (會員帳號) VALUES (?)");
mysqli_stmt_bind_param($stmt_order, "s", $userAccount);
if (mysqli_stmt_execute($stmt_order)) {
    $order_id = mysqli_insert_id($link); // 拿到訂單編號
    mysqli_stmt_close($stmt_order);

    // 插入訂單明細
    foreach ($quantities as $meal_id => $qty) {
        $qty = (int)$qty;
        $price_stmt = mysqli_prepare($link, "SELECT 價格,餐點名稱 FROM 餐點 WHERE 餐點編號 = ?");
        mysqli_stmt_bind_param($price_stmt, "i", $meal_id);
        mysqli_stmt_execute($price_stmt);
        mysqli_stmt_bind_result($price_stmt, $price, $meal_name);
        mysqli_stmt_fetch($price_stmt);
        mysqli_stmt_close($price_stmt);

        $item_total = $qty * $price;

        if ($qty > 0 && !empty($meal_id)) {
            $stmt = mysqli_prepare($link, "INSERT INTO 訂單明細 (訂單編號, 餐點編號, 餐點名稱, 數量, 價格) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "issii", $order_id, $meal_id, $meal_name, $qty, $item_total);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    echo "<h2 class='order-success-title'>✅ 訂單已成功送出！</h2>";
    echo "<p class='order-id'>您的訂單編號是：<strong>$order_id</strong></p>";
    echo "<h3 class='order-title'>您的訂單內容：</h3>";

    $sql_detail = "
    SELECT m.餐點名稱, m.價格, d.數量
    FROM 訂單明細 d
    JOIN 餐點 m ON d.餐點編號 = m.餐點編號
    WHERE d.訂單編號 = $order_id
    ";

    $result = mysqli_query($link, $sql_detail);
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table class='order-table'>";
        echo "<tr><th>餐點</th><th>價格</th><th>數量</th><th>小計</th></tr>";

        $total = 0;
        $content_summary = "";

        while ($row = mysqli_fetch_assoc($result)) {
            $subtotal = $row['價格'] * $row['數量'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['餐點名稱']) . "</td>";
            echo "<td>" . $row['價格'] . " 元</td>";
            echo "<td>" . $row['數量'] . "</td>";
            echo "<td>" . $subtotal . " 元</td>";
            echo "</tr>";
            $total += $subtotal;

            $content_summary .= $row['餐點名稱'] . " x " . $row['數量'] . "\n";
        }

        echo "<tr class='order-total'><td colspan='3'>總金額</td><td><strong>$total 元</strong></td></tr>";
        echo "</table>";

        $escaped_summary = mysqli_real_escape_string($link, $content_summary);
        $sql_update = "UPDATE 訂單 SET 總金額 = $total, 訂單內容 = '$escaped_summary' WHERE 訂單編號 = $order_id";
        mysqli_query($link, $sql_update);
    }

    echo "<a href='index.php' class='back-home'>🦐 回首頁</a>";

} else {
    echo "<h2>❌ 無法建立訂單</h2>";
    echo "<p>" . mysqli_error($link) . "</p>";
}

mysqli_close($link);
?>
</div>
</body>
</html>
