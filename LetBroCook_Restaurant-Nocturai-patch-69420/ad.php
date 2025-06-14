<?php
$link = mysqli_connect('localhost', 'root', '', '讓兄弟煮');
$password = password_hash('1234', PASSWORD_DEFAULT);
mysqli_query($link, "INSERT INTO account (username, password) VALUES ('admin', '$password')");
echo "✅ 管理員帳號建立成功";
?>
