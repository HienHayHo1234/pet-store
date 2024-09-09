<?php
session_start();

if (isset($_POST['login'])) {
    // Tiếp nhận user và pass từ form
    $username = trim(strip_tags($_POST['username']));
    $password = trim(strip_tags($_POST['password']));

    // Kết nối cơ sở dữ liệu
    require_once("functions.php");

    // Truy vấn kiểm tra username tồn tại
    $sql = "SELECT id, username, pass, idgroup FROM users WHERE username = :username AND idgroup = 1"; // idgroup 1 cho admin
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $username]);

    if ($stmt->rowCount() == 0) {
        $_SESSION['error'] = "Tài khoản admin không tồn tại";
    } else {
        // Lấy thông tin người dùng
        $user = $stmt->fetch();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['pass'])) {
            // Đăng nhập thành công
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_group'] = $user['idgroup'];

            header("Location: index.php"); // Chuyển đến trang chủ admin
            exit();
        } else {
            $_SESSION['error'] = "Mật khẩu không đúng";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link rel="stylesheet" href="../../asset/css/login.css">
</head>
<body>
    <div class="login-modal-content">
        <h2>Đăng Nhập Admin</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="login-error-message">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form id="loginForm" class="login-form">
            <div class="form-group-item">
                <label for="login-username">Tên đăng nhập:</label>
                <input type="text" id="login-username" name="username" required>
            </div>
            <div class="form-group-item">
                <label for="login-password">Mật khẩu:</label>
                <input type="password" id="login-password" name="password" required>
            </div>
            <div class="form-group-item">
                <label>
                    <input type="checkbox" name="status"> Ghi nhớ đăng nhập
                </label>
            </div>
            <hr>
            <div class="login-button-container">
                <input type="submit" value="Đăng Nhập">
                <button type="reset">Xóa</button>
            </div>
        </form>
    </div>
</body>
</html>

