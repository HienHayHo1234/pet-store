<?php
require 'config.php';
session_start();

$user_id = 1;

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    if ($quantity < 1) {
        echo json_encode(['success' => false, 'message' => 'Số lượng không hợp lệ.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE pet_id = ? AND user_id = ?");
        $stmt->execute([$quantity, $id, $user_id]);

        $stmt = $conn->prepare("
            SELECT pets.id, pets.name, pets.price, pets.priceSale, pets.urlImg, cart_items.quantity 
            FROM cart_items 
            JOIN pets ON cart_items.pet_id = pets.id 
            WHERE cart_items.user_id = ?
        ");
        $stmt->execute([$user_id]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['cart-items'] = $cartItems;

        $totalAmount = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $totalAmount += $item['priceSale'] * $item['quantity'];
            }
        }

        echo json_encode(['success' => true, 'totalAmount' => number_format($totalAmount, 0, ',', '.') . 'đ']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
}