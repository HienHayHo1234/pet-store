    <body>
        <div class = "tt">
            <div class="sidebar">
                <div class="welcome">
                    Xin chào, Nguyễn Hoàng Huy !
                </div>
                <h2>TRANG TÀI KHOẢN</h2>
                <a href="#">Thông tin tài khoản</a>
                <a href="profile.php?pageuser=orders">Đơn hàng của bạn</a>
                <a href="profile.php?pageuser=change_password">Đổi mật khẩu</a>
                <a href="address_book.php">Sổ địa chỉ</a>
                <a href="logout.php" class="logout">Đăng xuất</a>
            </div>
        </div>
        <main >
            <?php require_once("main_user.php"); ?>
        </main>
    </body>