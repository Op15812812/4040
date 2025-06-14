<?php
session_start();
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
    if (!$link) {
        die("資料庫連線錯誤：" . mysqli_connect_error());
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "所有欄位都必須填寫";
    } elseif ($password !== $confirm) {
        $error = "密碼與確認密碼不一致";
    } else {
        // 檢查帳號是否已存在
        $check = mysqli_prepare($link, "SELECT id FROM account WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "此帳號已被註冊";
        } else {
            // 插入新帳號
            $stmt = mysqli_prepare($link, "INSERT INTO account (username, password) VALUES (?, ?)");
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);
            if (mysqli_stmt_execute($stmt)) {
                $success = "註冊成功，請前往登入";
            } else {
                $error = "註冊失敗：" . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check);
    }

    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>註冊帳號</title>
    <link rel="stylesheet" href="register.css">
    <style>
        
    </style>
</head>
<body>
<div class="register-box">
    <h2>📝 註冊帳號</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="帳號" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <input type="password" name="password" placeholder="密碼" required>
        <input type="password" name="confirm" placeholder="確認密碼" required>
        <p>已經有帳號？<a href="login.php">登入</a></p>
        <button type="submit">註冊</button>
    </form>

    <?php if ($error): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success-msg"><?= htmlspecialchars($success) ?> 👉 <a href="login.php">登入</a></p>
    <?php endif; ?>
</div>
</body>
</html>
