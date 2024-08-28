<?php
session_start();

// Khai báo các thông số kết nối cơ sở dữ liệu
require 'config.php'; // Đảm bảo config.php có kết nối CSDL

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Vui lòng nhập đầy đủ thông tin.']);
    } else {
        try {
            // Kiểm tra thông tin đăng nhập
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            

            if ($user) {
                // Kiểm tra mật khẩu
                if ($password === $user['pass']) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    echo json_encode(['success' => true, 'password' => $user['pass']]); // Chỉ để kiểm tra, không khuyến khích gửi mật khẩu thực tế
                } else {
                    echo json_encode(['success' => false, 'error' => 'Mật khẩu không đúng.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Tên đăng nhập không tồn tại.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Lỗi khi truy vấn cơ sở dữ liệu.']);
        }
    }
}
?>
