<?php

// Nhận biến $page để biết người dùng đang truy cập trang gì
$page = trim(strip_tags($_GET['page'] ?? ''));

// Nhúng file chứa các hàm kết nối cơ sở dữ liệu
require_once "functions.php";

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản trị website</title>
    <style>
        header.row {
            height: 90px;
        }

        div.noidung > aside,
        div.noidung main {
            min-height: 500px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="row bg-info">
            <div class="col-12 text-center">
                <h1>Quản trị Website</h1>
            </div>
        </header>

        <div class="row noidung">
            <aside class="col-2 bg-dark text-white">
                <ul>
                    <li><a href="?page=pets_ds" class="text-white">Danh sách thú cưng</a></li>
                    <li><a href="?page=pets_them" class="text-white">Thêm thú cưng</a></li>
                    <li><a href="?page=danhmucpets_ds" class="text-white">Danh sách danh mục thú cưng</a></li>
                    <li><a href="?page=danhmucpets_them" class="text-white">Thêm danh mục thú cưng</a></li>
                    <li><a href="?page=cart_items_ds" class="text-white">Danh sách giỏ hàng</a></li>
                </ul>
            </aside>

            <main class="col-10 border">
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
                    case "danhmucpets_ds":
                        require_once 'danhmucpets_ds.php';
                        break;
                    case "danhmucpets_them":
                        require_once 'danhmucpets_them.php';
                        break;
                    case "danhmucpets_sua":
                        require_once 'danhmucpets_sua.php';
                        break;
                    case "cart_items_ds":
                        require_once 'cart_items_ds.php';
                        break;
                    default:
                        echo "<p>Chào mừng đến với trang quản trị hệ thống thú cưng!</p>";
                        break;
                }
                ?>
            </main>
        </div>
    </div>
</body>

</html>
