<?php

require 'config.php';

session_start();

header('Content-Type: application/json');

// Kiểm tra kết nối có tồn tại hay không
if (!$conn) {
    echo json_encode(['error' => "Kết nối cơ sở dữ liệu thất bại."]);
    exit;
}

try {
    $totalQuantity = 0;

    if (isset($_SESSION['user_id'])) {
        // Người dùng đã đăng nhập
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("
            SELECT SUM(cart_items.quantity) as totalQuantity
            FROM cart
            JOIN cart_items ON cart.id = cart_items.cart_id
            WHERE cart.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalQuantity = $result['totalQuantity'] ?? 0;
    } else {
        // Khách (guest)
        if (isset($_SESSION['guest_cart']) && is_array($_SESSION['guest_cart'])) {
            // Tính tổng số lượng sản phẩm trong giỏ hàng của khách
            $totalQuantity = array_sum($_SESSION['guest_cart']);
        }
    }

    echo json_encode(['totalQuantity' => $totalQuantity]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => "Có lỗi xảy ra khi kiểm tra giỏ hàng."]);
}
exit;