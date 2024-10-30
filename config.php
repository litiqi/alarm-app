<?php
$host = 'sql100.infinityfree.com';        // Địa chỉ MySQL
$db = 'if0_37622265_alarm_app';          // Tên cơ sở dữ liệu
$user = 'if0_37622265';       // Tên người dùng MySQL
$pass = 'q3214567Q';         // Mật khẩu của người dùng

// Thiết lập kết nối với MySQL bằng PDO
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Kết nối với MySQL
    $pdo = new PDO($dsn, $user, $pass);
    // Thiết lập chế độ báo lỗi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>
