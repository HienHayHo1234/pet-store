<link rel="stylesheet" href="../asset/css/user.css">
<link rel="stylesheet" href="../asset/css/profile.css">
    
<div class="container-index-user-flex">
    <div class="cnt-col">
            <div class="sidebar">
                <div class="welcome">
                    Xin chào, Nguyễn Hoàng Huy !
                </div>
                <h2>TRANG TÀI KHOẢN</h2>
                <a href="#">Thông tin tài khoản</a>
                <a href="index.php?page=index_user&pageuser=orders">Đơn hàng của bạn</a>
                <a href="index.php?page=index_user&pageuser=change_password">Đổi mật khẩu</a>
                <a href="index.php?page=index_user&address_book.php">Sổ địa chỉ</a>
                <a href="logout.php" class="logout">Đăng xuất</a>
            </div>
        </div>

        <div class="content-col">
            <?php $pageuser = isset($_GET['pageuser']) ? $_GET['pageuser'] : '';
                switch ($pageuser) {

                    case 'orders':
                        require_once 'orders.php';
                        break;
                    case 'change_password':
                        require_once 'change_password.php';
                        break;
                    case 'address_book':
                        require_once 'address_book.php';
                        break;
                    default:
                        require_once 'profile.php';
                        break;
                }?>
        </div>
</div>
    
