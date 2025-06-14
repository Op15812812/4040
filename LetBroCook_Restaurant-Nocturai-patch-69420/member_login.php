<?php
session_start(); // 放在最上面！

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
    if (!$link) {
        die("資料庫連線失敗: " . mysqli_connect_error());
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($link, "SELECT 密碼 FROM 會員 WHERE 使用者帳號 = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hash);
    mysqli_stmt_fetch($stmt);

    if ($hash && password_verify($password, $hash) || ($password == $hash)) {
        $_SESSION['member'] = $username;
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header("Location: member_center.php");
        exit;
    } else {
        $error = "❌ 帳號或密碼錯誤！";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員登入 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fff8ee;
            margin: 0;
            padding: 0;
        }
        .login-container {
            background-color: #fffaf0;
            width: 380px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        }
        h2 {
            text-align: center;
            color: #a0522d;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1.5px solid #ffcc99;
            border-radius: 8px;
            background-color: #fffdf9;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #ffa94d;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ff8c00;
        }
        .login-container p {
            margin-top: 15px;
            text-align: center;
        }
        .login-container p a {
            color: #cc6600;
            text-decoration: none;
            font-weight: bold;
        }
        .login-container p a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #555;
            font-size: 0.95em;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>🔐 會員登入</h2>
    <form method="POST" autocomplete="off" novalidate>
        <label for="username">帳號</label>
        <input type="text" id="username" name="username" required autofocus>
        
        <label for="password">密碼</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">登入</button>
    </form>

    <p>還沒有會員帳號？<a href="member_register.php">註冊</a></p>
    <p><a href="forgot_password.php">🔑 忘記密碼？</a></p>

    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <a href="index.php" class="back-link">🦐 返回首頁</a>
</div>

</body>
</html>
