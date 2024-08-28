<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sổ địa chỉ</title>
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
            <h2>Sổ địa chỉ</h2>
            <?php
            // Kết nối cơ sở dữ liệu và truy vấn danh sách địa chỉ ở đây
            // Ví dụ đơn giản:
            echo "<p>Danh sách địa chỉ sẽ được hiển thị ở đây.</p>";
            ?>
        </div>
    </div>
</body>
</html>
