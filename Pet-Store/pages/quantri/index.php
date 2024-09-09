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
        font-family: 'Roboto', 'Arial', sans-serif;
    }

    html, body {
        height: 100%;
        width: 100%;
        background-color: #f5f7fa;
        color: #2c3e50;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
    }

    header {
        height: 80px;
        background-color: #3498db;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 10px rgba(52, 152, 219, 0.2);
    }

    header h1 {
        color: white;
        font-size: 2.2rem;
        font-weight: 500;
    }

    .noidung {
        display: flex;
        flex: 1;
        overflow: hidden;
    }

    aside {
        background-color: #2c3e50;
        padding: 25px;
        width: 240px;
        overflow-y: auto;
    }

    aside ul {
        list-style: none;
    }

    aside ul li {
        margin-bottom: 15px;
    }

    aside ul li a {
        color: #ecf0f1;
        text-decoration: none;
        font-size: 1.1rem;
        padding: 12px 15px;
        display: block;
        background-color: #34495e;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    aside ul li a:hover {
        background-color: #3498db;
        transform: translateX(5px);
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
        padding: 20px;
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
                    <li><a href="?page=orders">Danh sách đơn hàng</a></li>
                    <li><a href="?page=users">Quản lý người dùng</a></li>
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
                        require_once 'login.php';
                        break;
                }
                ?>
            </main>
        </div>
    </div>
</body>

</html>
