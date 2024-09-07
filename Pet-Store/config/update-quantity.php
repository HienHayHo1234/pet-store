<?php
require 'config.php';
session_start();

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $pet_id = $_POST['id'];
    $quantity = $_POST['quantity'];

    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ.']);
        exit;
    }

    try {
        if ($is_logged_in) {
            $user_id = $_SESSION['user_id'];
            
            // Lấy cart_id
            $stmt = $conn->prepare("SELECT cart_id FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $cart = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cart) {
                throw new Exception("Không tìm thấy giỏ hàng.");
            }

            $cart_id = $cart['cart_id'];

            // Cập nhật số lượng trong database
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND pet_id = ?");
            $stmt->execute([$quantity, $cart_id, $pet_id]);

            // Lấy thông tin giỏ hàng mới từ database
            $stmt = $conn->prepare("
                SELECT pets.id, pets.name, pets.price, pets.priceSale, pets.urlImg, cart_items.quantity, cart_items.price as item_price
                FROM cart_items 
                JOIN pets ON cart_items.pet_id = pets.id 
                WHERE cart_items.cart_id = ?
            ");
            $stmt->execute([$cart_id]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Xử lý giỏ hàng trong session cho người dùng chưa đăng nhập
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Cập nhật số lượng trong session
            $_SESSION['cart'][$pet_id] = $quantity;

            // Lấy thông tin sản phẩm từ database
            $stmt = $conn->prepare("SELECT id, name, price, priceSale, urlImg FROM pets WHERE id = ?");
            $stmt->execute([$pet_id]);
            $pet = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$pet) {
                throw new Exception("Không tìm thấy sản phẩm.");
            }

            // Cập nhật thông tin giỏ hàng trong session
            $cartItems = [];
            foreach ($_SESSION['cart'] as $id => $qty) {
                $stmt->execute([$id]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($item) {
                    $item['quantity'] = $qty;
                    $item['item_price'] = $item['priceSale'] > 0 ? $item['priceSale'] : $item['price'];
                    $cartItems[] = $item;
                }
            }
        }

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