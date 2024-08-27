<?php
require 'config.php';

// Giả sử user_id là 1, bạn có thể thay đổi theo hệ thống của bạn
$user_id = 1;

// Gửi header JSON
header('Content-Type: application/json');

// Lấy pet_id từ POST request
if (isset($_POST['action']) && isset($_POST['pet_id'])) {
    $action = $_POST['action'];
    $pet_id = $_POST['pet_id'];

    $response = array('success' => false, 'message' => '');

    if ($action === 'add') {
        // Thêm sản phẩm vào giỏ hàng
        addToCart($user_id, $pet_id, 1, $conn);
        $response['success'] = true;
        $response['message'] = 'Sản phẩm đã được thêm vào giỏ hàng!';
    } elseif ($action === 'remove') {
        // Xóa sản phẩm khỏi giỏ hàng
        removeFromCart($user_id, $pet_id, $conn);
        $response['success'] = true;
        $response['message'] = 'Sản phẩm đã được xóa khỏi giỏ hàng!';
    } else {
        $response['message'] = 'Hành động không hợp lệ!';
    }

    echo json_encode($response);
} else {
    $response = array('success' => false, 'message' => 'Thiếu thông tin cần thiết!');
    echo json_encode($response);
}

function addToCart($user_id, $pet_id, $quantity = 1, $conn)
{
    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND pet_id = ?");
    $stmt->execute([$user_id, $pet_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Nếu sản phẩm đã có trong giỏ, cộng thêm số lượng
        $new_quantity = $item['quantity'] + $quantity;

        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        $stmt->execute([$new_quantity, $item['id']]);
    } else {
        // Nếu sản phẩm chưa có trong giỏ, thêm mới
        $stmt = $conn->prepare("INSERT INTO cart_items (user_id, pet_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $pet_id, $quantity]);
    }
}

function removeFromCart($user_id, $pet_id, $conn)
{
    // Xóa sản phẩm khỏi giỏ hàng
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND pet_id = ?");
    $stmt->execute([$user_id, $pet_id]);
}