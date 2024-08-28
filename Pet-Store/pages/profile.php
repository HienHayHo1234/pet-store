<?php
session_start();
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Tài Khoản</title>
    <link rel="stylesheet" href="../asset/css/profile.css">
</head>
<body>
    <h2>Thông Tin Tài Khoản</h2>
    <?php if ($user): ?>
        <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <!-- Hiển thị thêm thông tin khác nếu cần -->
    <?php else: ?>
        <p>Không tìm thấy thông tin người dùng.</p>
    <?php endif; ?>
</body>
</html>
