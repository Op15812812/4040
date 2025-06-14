<audio id="login-sound" autoplay>
    <source src="login.ogg" type="audio/mpeg">
    您的瀏覽器不支援音效播放。
</audio>

<script>
    // 自動播放登入成功音效（需要與使用者互動後才會播放於某些瀏覽器）
    document.addEventListener("DOMContentLoaded", function() {
        const audio = document.getElementById("login-sound");
        if (audio) {
            audio.play().catch(err => {
                console.warn("自動播放失敗，瀏覽器可能限制了自動播放：", err);
            });
        }
    });
</script>
<?php
session_start();

if (!isset($_SESSION['member'])) {
    header("Location: member_login.php");
    exit;
}

$member_username = $_SESSION['member'];

$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
if (!$link) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

// 處理上傳大頭貼
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_tmp = $_FILES['avatar']['tmp_name'];
    $file_name = basename($_FILES['avatar']['name']);
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array(strtolower($ext), $allowed)) {
        $new_name = $member_username . "_avatar." . $ext;
        $target_file = $target_dir . $new_name;
        if (move_uploaded_file($file_tmp, $target_file)) {
            // 更新會員資料庫的大頭貼欄位
            $stmt = mysqli_prepare($link, "UPDATE `會員` SET `大頭貼` = ? WHERE `使用者帳號` = ?");
            mysqli_stmt_bind_param($stmt, "ss", $target_file, $member_username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

// 撈取會員資料（包含大頭貼）
$stmt = mysqli_prepare($link, "SELECT `姓名`, `電話`, `電子郵件`, `註冊時間`, `大頭貼` FROM `會員` WHERE `使用者帳號` = ?");
mysqli_stmt_bind_param($stmt, "s", $member_username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $phone, $email, $register_time, $avatar);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>會員中心 - 讓兄弟煮</title>
    <style>
        body {
            font-family: "微軟正黑體", sans-serif;
            background-color: #fffaf0;
            padding: 40px;
            text-align: center;
        }

        .member-info {
            background-color: #fff;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #d2b48c;
        }

        .member-info h2 {
            color: #8b4513;
            margin-bottom: 20px;
        }

        .member-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #ccc;
        }

        .button {
            display: inline-block;
            margin: 15px;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #ffae42;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ff8c00;
        }

        .upload-form {
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="member-info">
    <h2>👤 會員中心</h2>

    <?php if (!empty($avatar) && file_exists($avatar)): ?>
        <img src="<?= htmlspecialchars($avatar) ?>" class="avatar" alt="大頭貼">
    <?php else: ?>
        <img src="default_avatar.jpg" class="avatar" alt="預設大頭貼">
    <?php endif; ?>

    <form class="upload-form" action="" method="post" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/*" required>
        <button type="submit" class="button">上傳大頭貼</button>
    </form>

    <p><strong>帳號：</strong> <?= htmlspecialchars($member_username) ?></p>
    <p><strong>姓名：</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>電話：</strong> <?= htmlspecialchars($phone) ?></p>
    <p><strong>電子郵件：</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>註冊時間：</strong> <?= htmlspecialchars($register_time) ?></p>

    <a href="index.php" class="button">🦐 返回首頁</a>
    <a href="logout.php" class="button">🚪 登出</a>
</div>

</body>
</html>
