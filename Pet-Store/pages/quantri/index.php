<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập với tư cách admin chưa
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Kiểm tra xem người dùng có phải là admin không (giả sử admin có idgroup là 1)
if (!isset($_SESSION['admin_group']) || $_SESSION['admin_group'] != 1) {
    // Nếu không phải admin, đăng xuất và chuyển hướng đến trang đăng nhập
    session_unset();
    session_destroy();
    header("Location: login.php?error=unauthorized");
    exit();
}

// Tùy chọn: Kiểm tra thời gian không hoạt động
$inactive = 1800; // 30 phút
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    // Nếu quá thời gian không hoạt động, đăng xuất
    session_unset();
    session_destroy();
    header("Location: login.php?error=timeout");
    exit();
}
$_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động

// Tùy chọn: Tái tạo ID phiên để ngăn chặn fixation attacks
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} else if (time() - $_SESSION['created'] > 1800) { // Giảm xuống 30 giây để test
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// Định nghĩa biến $page
$page = isset($_GET['page']) ? $_GET['page'] : '';

// Nếu mọi kiểm tra đều pass, admin có thể truy cập trang này
require 'functions.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị website</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    html, body {
        height: 100%;
        width: 100%;
        background-color: #f4f7f6;
        color: #333;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
    }

    header {
        height: 70px;
        background-color: #2c3e50;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    header h1 {
        color: white;
        font-size: 1.8rem;
        font-weight: 500;
    }

    .noidung {
        display: flex;
        flex: 1;
        overflow: hidden;
    }

    aside {
        background-color: #34495e;
        width: 250px;
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    aside ul {
        list-style: none;
        padding: 20px 0;
    }

    aside ul li {
        margin-bottom: 10px;
    }

    aside ul li a {
        color: #ecf0f1;
        text-decoration: none;
        font-size: 1rem;
        padding: 12px 25px;
        display: block;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    aside ul li a:hover, aside ul li a.active {
        background-color: #2c3e50;
        border-left-color: #3498db;
    }

    main {
        flex: 1;
        background-color: white;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        overflow-y: auto;
    }

    main p {
        font-size: 1.1rem;
        color: #34495e;
        line-height: 1.6;
    }

    footer {
        text-align: center;
        font-size: 0.9rem;
        color: #7f8c8d;
        padding: 15px;
        background-color: #ecf0f1;
        border-top: 1px solid #bdc3c7;
    }

    footer a {
        color: #3498db;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    footer a:hover {
        color: #2980b9;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        aside {
            width: 70px;
        }

        aside ul li a {
            padding: 15px;
            text-align: center;
        }

        aside ul li a span {
            display: none;
        }
    }
</style>

</head>

<body>
    <div class="container">
        <header>
            <h1>QUẢN TRỊ WEBSITE</h1>
        </header>

        <div class="noidung">
            <aside>
                <ul>
                    <li><a href="index.php?page=pets_ds"><i class="fas fa-paw"></i> <span>Danh sách thú cưng</span></a></li>
                    <li><a href="index.php?page=pets_them"><i class="fas fa-plus"></i> <span>Thêm thú cưng</span></a></li>   
                    <li><a href="index.php?page=orders"><i class="fas fa-shopping-cart"></i> <span>Danh sách đơn hàng</span></a></li>
                    <li><a href="index.php?page=users"><i class="fas fa-users"></i> <span>Quản lý người dùng</span></a></li>
                </ul>
            </aside>

            <main>
                <?php
                // Nhúng các trang con vào vùng nội dung chính dựa vào biến $page
                switch ($page) {
                    case "pets_ds":
                        require_once 'pets_ds.php';
                        break;
                    case "pets_them":
                        require_once 'pets_them.php';
                        break;
                    case "pets_sua":
                        require_once 'pets_sua.php';
                        break;
                    case "orders":
                        require_once 'orders.php';
                        break;
                    case "orders_detail":
                        require_once 'orders_detail.php';
                        break;
                    case "users":
                        require_once 'users.php';
                        break;
                    default:
                        echo "<p>Chào mừng đến với trang quản trị hệ thống thú cưng!</p>";
                        break;
                }
                ?>
            </main>
        </div>
        <footer>
            <p>&copy; 2023 Quản trị Website Thú Cưng. Thiết kế bởi <a href="#">Your Name</a></p>
        </footer>
    </div>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>
