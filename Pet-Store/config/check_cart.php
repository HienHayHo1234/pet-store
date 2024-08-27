<?php

require 'config.php';

session_start();

$user_id = 1; // Hoặc lấy từ session của người dùng đang đăng nhập

// Kiểm tra kết nối có tồn tại hay không
if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại.");
}

$stmt = $conn->prepare("
SELECT COUNT(*) as itemCount
FROM cart_items
WHERE user_id = ?
");
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['itemCount' => $result['itemCount']]);
