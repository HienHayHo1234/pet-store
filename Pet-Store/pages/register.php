<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
require '../config/config.php';

// Xử lý form đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
    } elseif ($password !== $confirmPassword) {
        echo "Mật khẩu xác nhận không khớp.";
    } else {
        // Kiểm tra nếu người dùng đã tồn tại
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $checkUser->bindParam(':username', $username);
        $checkUser->bindParam(':email', $email);
        $checkUser->execute();

        if ($checkUser->rowCount() > 0) {
            echo "Tài khoản hoặc email đã tồn tại. Vui lòng <a href='index.php'>đăng nhập</a>.";
        } else {
            // Lưu mật khẩu không mã hóa
            $sql = "INSERT INTO users (username, email, pass) VALUES (:username, :email, :pass)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass', $password); // Lưu mật khẩu trực tiếp

            if ($stmt->execute()) {
                // Redirect đến trang index.php với query string
                header("Location: index.php?showlogin=true");
                exit();
            } else {
                echo "Đã xảy ra lỗi khi đăng ký.";
            }
        }
    }
}
?>

<link rel="stylesheet" href="../asset/css/register.css">
<!-- Modal Form Đăng Ký -->
<div id="registerModal" class="modal" style="display:none">
    <div class="modal-content">
        <span id="closeRegisterModalButton" class="close">&times;</span>
        <!-- Mũi tên quay lại form đăng nhập -->
        <span id="backToLogin" class="back-arrow">&#8592; Quay lại</span> 
        <h2>Đăng Ký</h2>
        <form action="register.php" method="post" class ="form-register">
            <label for="register-username">Tên đăng nhập</label>
            <input type="text" id="register-username" name="username" required><br>
            <label for="register-email">Email</label><br>
            <input type="email" id="register-email" name="email" required><br>
            <label for="register-password">Mật khẩu</label><br>
            <input type="password" id="register-password" name="password" required><br>
            <label for="register-confirmPassword">Xác nhận mật khẩu</label><br>
            <input type="password" id="register-confirmPassword" name="confirmPassword" required><br>
            <div class="button-container">
                <button type="submit" name = "btn1">Gửi</button>
                <button type="reset">Xóa</button>
            </div>
        </form>
    </div>
</div>
<script src="../asset/js/register.js"></script>
