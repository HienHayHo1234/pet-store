<?php
require 'config.php';

// Gửi header JSON
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

// Kiểm tra xem có phải là yêu cầu POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();

        // Kiểm tra trạng thái đăng nhập
        session_start();
        $is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

        if ($is_logged_in) {
            $user_id = $_SESSION['user_id'];
        } else {
            // Nếu không đăng nhập, sử dụng guest_id từ cookie
            if (!isset($_COOKIE['guest_id'])) {
                $guest_id = bin2hex(random_bytes(16));
                setcookie('guest_id', $guest_id, time() + (86400 * 30), '/', '', true, true);
            } else {
                $guest_id = $_COOKIE['guest_id'];
            }
            $user_id = $guest_id;
        }

        // Lấy thông tin từ form
        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $total_amount = str_replace(['đ', ','], '', $_POST['total_amount'] ?? '0');
        $pet_ids = json_decode($_POST['pet_ids'], true);

        // Kiểm tra các trường bắt buộc
        if (empty($name) || empty($address) || empty($phone) || empty($pet_ids)) {
            throw new Exception("Vui lòng điền đầy đủ thông tin.");
        }

        // Tạo đơn hàng mới
        $stmt = $conn->prepare("INSERT INTO orders (user_id, totalAmount, status) VALUES (?, ?, 'Pending')");
        $stmt->execute([$user_id, $total_amount]);
        
        $order_id = $conn->lastInsertId();

        // Thêm thông tin chi tiết đơn hàng
        $stmt = $conn->prepare("INSERT INTO order_details (idOrder, pet_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        foreach ($pet_ids as $pet_id) {
            // Lấy thông tin sản phẩm từ database
            $pet_stmt = $conn->prepare("SELECT price, priceSale FROM pets WHERE id = ?");
            $pet_stmt->execute([$pet_id]);
            $pet = $pet_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($pet) {
                $price = $pet['priceSale'] > 0 ? $pet['priceSale'] : $pet['price'];
                $stmt->execute([$order_id, $pet_id, 1, $price]);
                
                // Xóa sản phẩm khỏi giỏ hàng
                if ($is_logged_in) {
                    $delete_stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = ?) AND pet_id = ?");
                    $delete_stmt->execute([$user_id, $pet_id]);
                }
            }
        }

        // Cập nhật thông tin người dùng nếu là khách
        if (!$is_logged_in) {
            $stmt = $conn->prepare("INSERT INTO users (username, email, phone, address, pass, idgroup) VALUES (?, ?, ?, ?, ?, 0) ON DUPLICATE KEY UPDATE phone = ?, address = ?");
            $stmt->execute([$name, $guest_id . '@guest.com', $phone, $address, password_hash($guest_id, PASSWORD_DEFAULT), $phone, $address]);
            
            // Xóa giỏ hàng trong cookie
            setcookie('guest_cart', '', time() - 3600, '/', '', true, true);
        }

        $conn->commit();
        
        $response['success'] = true;
        $response['message'] = 'Đơn hàng đã được tạo thành công!';
        $response['order_id'] = $order_id;
    } catch (Exception $e) {
        $conn->rollBack();
        $response['message'] = 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage();
        error_log($e->getMessage());
    }
} else {
    $response['message'] = 'Phương thức không được hỗ trợ.';
}

echo json_encode($response);
