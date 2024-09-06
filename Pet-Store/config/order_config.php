<?php
require 'config.php';

// Gửi header JSON
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

// Kiểm tra xem có phải là yêu cầu POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        // Lấy thông tin từ form
        $user_id = $_POST['user_id'] ?? 11; // Giả sử user_id mặc định là 1 nếu không có
        $pet_id = $_POST['pet_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $total_amount = str_replace(['đ', ','], '', $_POST['total_amount'] ?? '0');

        // Kiểm tra các trường bắt buộc
        if (empty($pet_id) || empty($name) || empty($address) || empty($phone)) {
            throw new Exception("Vui lòng điền đầy đủ thông tin.");
        }

        // Tạo đơn hàng mới
        $stmt = $conn->prepare("INSERT INTO orders (user_id, pet_id, customer_name, address, phone, total_amount, order_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $pet_id, $name, $address, $phone, $total_amount]);
        
        $conn->commit();
        
        $response['success'] = true;
        $response['message'] = 'Đơn hàng đã được tạo thành công!';
    } catch (Exception $e) {
        $conn->rollBack();
        $response['message'] = 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage();
        error_log($e->getMessage());
    }
} else {
    $response['message'] = 'Phương thức không được hỗ trợ.';
}

echo json_encode($response);
