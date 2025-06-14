<?php
// 開始處理 POST 表單送出的資料
$success = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    $link = mysqli_connect("localhost", "root", "", "讓兄弟煮");
    if (!$link) {
        $error = "❌ 資料庫連線失敗：" . mysqli_connect_error();
    } else {
        // 檢查帳號是否已存在
        $check = mysqli_prepare($link, "SELECT * FROM 會員 WHERE 使用者帳號 = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "⚠️ 帳號已存在，請使用其他帳號。";
        } else {
            $stmt = mysqli_prepare($link, "INSERT INTO 會員 (使用者帳號, 密碼, 姓名, 電話, 電子郵件, 註冊時間) VALUES (?, ?, ?, ?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, "sssss", $username, $password, $name, $phone, $email);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $success = true;
            } else {
                $error = "❌ 註冊失敗，請再試一次。";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員註冊 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fffaf0;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #8b4513;
        }
        form {
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #d2b48c;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #d2b48c;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #ffa500;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #ff8c00;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin: 20px auto;
            max-width: 400px;
            padding: 12px;
            border-radius: 8px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .home-button {
            display: block;
            width: 160px;
            margin: 30px auto 0;
            text-align: center;
            background-color: #20b2aa;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .home-button:hover {
            background-color: #008b8b;
        }
    </style>
</head>
<body>
    <h2>📝 會員註冊</h2>

    <?php if ($success): ?>
        <div class="message success">✅ 註冊成功！請前往<a href="member_login.php">登入頁面。</a></div>
    <?php elseif (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">使用者帳號</label>
        <input type="text" name="username" id="username" required>

        <label for="password">密碼</label>
        <input type="password" name="password" id="password" required>

        <label for="name">姓名</label>
        <input type="text" name="name" id="name" required>

        <label for="phone">電話</label>
        <input type="text" name="phone" id="phone" required>

        <label for="email">電子郵件</label>
        <input type="email" name="email" id="email" required>

        <button type="submit" class="submit-btn">註冊</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
    已有帳號？<a href="member_login.php">登入</a>
</p>


    <a href="index.php" class="home-button">🦐 返回首頁</a>
</body>
</html>
