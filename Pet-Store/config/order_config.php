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
        $is_logged_in = isset($_SESSION['login_id']); // Giả sử bạn lưu ID người dùng trong session

        if ($is_logged_in) {
            $user_id = $_SESSION['login_id'];
        } else {
            $guest_id = $_COOKIE['guest_id'] ?? bin2hex(random_bytes(16));
            setcookie('guest_id', $guest_id, time() + (86400 * 30), '/', '', true, true);
            
            // Kiểm tra xem guest user đã tồn tại chưa
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $guest_email = $guest_id . '@guest.com';
            $stmt->execute([$guest_email]);
            $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing_user) {
                $user_id = $existing_user['id'];
            } else {
                // Tạo mới guest user
                $stmt = $conn->prepare("INSERT INTO users (username, fullname, email, phone, address, pass, idgroup) VALUES ('guest', ?, ?, ?, ?, ?, 0)");
                $stmt->execute([$_POST['name'], $guest_email, $_POST['phone'], $_POST['address'], password_hash($guest_id, PASSWORD_DEFAULT)]);
                $user_id = $conn->lastInsertId();
            }
            
            setcookie('guest_cart', '', time() - 3600, '/', '', true, true);
        }

        // Validate and sanitize input
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $total_amount = filter_var(str_replace(['đ', ','], '', $_POST['total_amount'] ?? '0'), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $pet_ids = json_decode($_POST['pet_ids'], true);

        if (empty($name) || empty($address) || empty($phone) || empty($pet_ids)) {
            throw new Exception("Vui lòng điền đầy đủ thông tin.");
        }

        // Create new order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, totalAmount, status) VALUES (?, ?, ?)");
        $status = 'Chờ xử lý';
        $stmt->execute([$user_id, $total_amount, $status]);
        $order_id = $conn->lastInsertId();

        // Add order details
        $stmt = $conn->prepare("INSERT INTO order_details (order_id, pet_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($pet_ids as $pet_id) {
            $pet_stmt = $conn->prepare("SELECT price, priceSale FROM pets WHERE id = ?");
            $pet_stmt->execute([$pet_id]);
            $pet = $pet_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($pet) {
                $price = $pet['priceSale'] > 0 ? $pet['priceSale'] : $pet['price'];
                $stmt->execute([$order_id, $pet_id, 1, $price]);
                
                if ($is_logged_in) {
                    $delete_stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = (SELECT cart_id FROM cart WHERE user_id = ?) AND pet_id = ?");
                    $delete_stmt->execute([$user_id, $pet_id]);
                }
            }
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
