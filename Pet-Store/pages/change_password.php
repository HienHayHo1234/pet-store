<?php

// Kết nối đến cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$db_username = "root";
$db_password = "";

// Tạo kết nối
$conn = new mysqli($host, $db_username, $db_password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Lấy tên người dùng từ session
    $username = $conn->real_escape_string($_SESSION['username']);

    // Kiểm tra xem mật khẩu hiện tại có đúng không
    $sql = "SELECT pass FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kiểm tra mật khẩu hiện tại
        if ($current_password === $row['pass']) {
            // Kiểm tra xem mật khẩu mới và xác nhận mật khẩu có khớp không
            if ($new_password === $confirm_password) {
                // Mã hóa mật khẩu mới
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Cập nhật mật khẩu trong cơ sở dữ liệu
                $update_sql = "UPDATE users SET pass = '$hashed_password' WHERE username = '$username'";

                if ($conn->query($update_sql) === TRUE) {
                    echo "Mật khẩu đã được cập nhật thành công.";
                } else {
                    echo "Có lỗi xảy ra khi cập nhật mật khẩu: " . $conn->error;
                }
            } else {
                echo "Mật khẩu mới và xác nhận mật khẩu không khớp.";
            }
        } else {
            echo "Mật khẩu hiện tại không đúng.";
        }
    } else {
        echo "Người dùng không tồn tại.";
    }
}

// Đóng kết nối
$conn->close();
?>

<div class="tt">
    <div class="content">
        <h2>Đổi mật khẩu</h2>
        <form class="profile" action="" method="post">
            <label for="current_password">Mật khẩu hiện tại:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Nhập lại mật khẩu mới:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Đổi mật khẩu</button>
        </form>
    </div>
</div>
