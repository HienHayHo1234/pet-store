<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <style>
        /* CSS chung từ ví dụ trước */
    </style>
</head>
<body>
    <div class="tt">
        <div class="sidebar">
            <div class="welcome">
                Xin chào, Nguyễn Hoàng Huy !
            </div>
            <h2>TRANG TÀI KHOẢN</h2>
            <a href="profile.php">Thông tin tài khoản</a>
            <a href="orders.php">Đơn hàng của bạn</a>
            <a href="change_password.php">Đổi mật khẩu</a>
            <a href="address_book.php">Sổ địa chỉ</a>
            <a href="logout.php" class="logout">Đăng xuất</a>
        </div>

        <div class="content">
            <h2>Đổi mật khẩu</h2>
            <form class="profile" action="change_password_action.php" method="post">
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
</body>
</html>
