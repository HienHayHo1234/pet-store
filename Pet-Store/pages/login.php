<?php
session_start();

// Khai báo các thông số kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Lỗi kết nối cơ sở dữ liệu.']);
    exit();
}

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password)) {
        echo json_encode(['error' => 'Vui lòng nhập đầy đủ thông tin.']);
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
                if (password_verify($password, $user['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['error' => 'Mật khẩu không đúng.']);
                }
            } else {
                echo json_encode(['error' => 'Tên đăng nhập không tồn tại.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Lỗi khi truy vấn cơ sở dữ liệu.']);
        }
    }
}
