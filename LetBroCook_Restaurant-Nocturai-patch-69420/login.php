<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($link, "SELECT password FROM account WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hash);
    mysqli_stmt_fetch($stmt);

    if ($hash && password_verify($password, $hash)) {
        $_SESSION['admin'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error = "帳號或密碼錯誤！";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>登入 - 讓兄弟煮</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-container">
    <h2>🔐 管理員登入</h2>
    <form method="POST" autocomplete="off" novalidate>
        <label for="username">帳號</label>
        <input type="text" id="username" name="username" required autofocus>
        
        <label for="password">密碼</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">登入</button>
    </form>
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <a href="index.php" class="back-link">🦐 返回首頁</a>
</div>

</body>
</html>
