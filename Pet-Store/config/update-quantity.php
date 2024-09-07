<?php
require 'config.php';
session_start();

// Kiểm tra trạng thái đăng nhập
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Sử dụng guest_id nếu không đăng nhập
    if (!isset($_SESSION['guest_id'])) {
        $_SESSION['guest_id'] = uniqid('guest_', true);
    }
    $user_id = $_SESSION['guest_id'];
}

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $pet_id = $_POST['id'];
    $quantity = $_POST['quantity'];

    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ.']);
        exit;
    }

    try {
        // Lấy cart_id
        $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart) {
            throw new Exception("Không tìm thấy giỏ hàng.");
        }

        $cart_id = $cart['cart_id'];

        // Cập nhật số lượng
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND pet_id = ?");
        $stmt->execute([$quantity, $cart_id, $pet_id]);

        // Lấy thông tin giỏ hàng mới
        $stmt = $conn->prepare("
            SELECT pets.id, pets.name, pets.price, pets.priceSale, pets.urlImg, cart_items.quantity, cart_items.price as item_price
            FROM cart_items 
            JOIN pets ON cart_items.pet_id = pets.id 
            WHERE cart_items.cart_id = ?
        ");
        $stmt->execute([$cart_id]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['cart-items'] = $cartItems;

        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $totalAmount += $item['item_price'] * $item['quantity'];
            }
        }

        echo json_encode(['success' => true, 'totalAmount' => number_format($totalAmount, 0, ',', '.') . 'đ']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
}