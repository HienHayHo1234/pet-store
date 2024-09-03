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
    <title>Quản trị website</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }

        header {
            height: 90px;
            background-color: #17a2b8;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }

        header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .noidung {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        aside {
            background-color: #343a40;
            padding: 20px;
            width: 250px;
            overflow-y: auto;
            min-height: 100%;
        }

        aside ul {
            list-style: none;
        }

        aside ul li {
            margin-bottom: 15px;
        }

        aside ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 10px;
            display: block;
            background-color: #495057;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        aside ul li a:hover {
            background-color: #007bff;
        }

        main {
            flex: 1;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        main p {
            font-size: 1.2rem;
            color: #666;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            color: #888;
            padding: 15px;
            background-color: #f0f2f5;
            border-top: 1px solid #ddd;
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>Quản trị Website</h1>
        </header>

        <div class="noidung">
            <aside>
                <ul>
                    <li><a href="?page=pets_ds">Danh sách thú cưng</a></li>
                    <li><a href="?page=pets_them">Thêm thú cưng</a></li>   
                    <li><a href="?page=cart_items_ds">Danh sách đơn hàng</a></li>
                    <li><a href="?page=#">Tình trạng đơn hàng</a></li>
                    <li><a href="?page=#">Tình trạng vận chuyển</a></li>
                    <li><a href="?page=#">Quản lý người dùng</a></li>
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
