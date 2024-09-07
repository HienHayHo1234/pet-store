<?php
require '../config/config.php'; // Đảm bảo file này chứa kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Kiểm tra xem các trường đã được điền đầy đủ
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'error' => 'Vui lòng điền đầy đủ thông tin.']);
        exit;
    }

    // Kiểm tra xác nhận mật khẩu
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'error' => 'Mật khẩu xác nhận không khớp.']);
        exit;
    }

    // Kiểm tra độ dài mật khẩu
    if (strlen($password) < 1) {
        echo json_encode(['success' => false, 'error' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
        exit;
    }

    try {
        // Kiểm tra xem username hoặc email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'error' => 'Tên đăng nhập hoặc email đã tồn tại.']);
            exit;
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng mới vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO users (username, email, pass) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.']);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại sau.']);
    }
    exit;
}
?>

<link rel="stylesheet" href="../asset/css/register.css">
<!-- Modal Form Đăng Ký -->
<div id="registerModal" class="register-modal" style="display:none">
    <div class="register-modal-content">
        <span id="closeRegisterModalButton" class="register-close">&times;</span>
        <!-- Mũi tên quay lại form đăng nhập -->
        <span id="backToLogin" class="register-back-arrow">&#8592; Quay lại</span> 
        <h2>Đăng Ký</h2>
        <div id="register-error-message" style="color: red;"></div>
        <form id="registerForm" class="register-form">
            <label for="register-username">Tên đăng nhập</label>
            <input type="text" id="register-username" name="username" required><br>
            <label for="register-email">Email</label><br>
            <input type="email" id="register-email" name="email" required><br>
            <label for="register-password">Mật khẩu</label><br>
            <input type="password" id="register-password" name="password" required><br>
            <label for="register-confirmPassword">Xác nhận mật khẩu</label><br>
            <input type="password" id="register-confirmPassword" name="confirmPassword" required><br>
            <div class="register-button-container">
                <button type="submit" id="registerSubmitButton" class="register-submit-btn">Gửi</button>
                <button type="reset" class="register-reset-btn">Xóa</button>
            </div>
        </form>
    </div>
</div>
<script src="../asset/js/register.js"></script>
