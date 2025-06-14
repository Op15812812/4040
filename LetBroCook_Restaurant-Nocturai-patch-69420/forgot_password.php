<?php
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    $link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
    if (!$link) {
        die("❌ 資料庫連線錯誤：" . mysqli_connect_error());
    }

    // 確認帳號與 email 是否匹配
    $stmt = mysqli_prepare($link, "SELECT 會員編號 FROM 會員 WHERE 使用者帳號 = ? AND 電子郵件 = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // 匹配成功，重設密碼
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
         $update = mysqli_prepare($link, "UPDATE 會員 SET 密碼 = ? WHERE 使用者帳號 = ?");
        mysqli_stmt_bind_param($update, "ss", $hashed, $username);
        if (mysqli_stmt_execute($update)) {
        $success = "✅ 密碼已重設，請重新登入。";
    } else {
        $error = "❌ 密碼重設失敗：" . mysqli_error($link);
    }
        mysqli_stmt_close($update);
    } else {
        $error = "❌ 帳號與 Email 不符，請再確認。";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>忘記密碼 - 讓兄弟煮</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <h2>🔑 忘記密碼</h2>
    <form method="POST">
        <label for="username">帳號</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="new_password">新密碼</label>
        <input type="password" id="new_password" name="new_password" required>

        <button type="submit">重設密碼</button>
    </form>

    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success-message"><?= htmlspecialchars($success) ?> 👉 <a href="member_login.php">前往登入</a></p>
    <?php endif; ?>

    <a href="index.php" class="back-link">🦐 返回首頁</a>
</div>

</body>
</html>
