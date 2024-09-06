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
        background-color: #f4f7f9;
        color: #333;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
    }

    header {
        height: 80px;
        background-color: #007bff;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        margin-bottom: 0;
    }

    header h1 {
        color: white;
        font-size: 2rem;
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
        width: 220px;
        overflow-y: auto;
        min-height: 100%;
    }

    aside ul {
        list-style: none;
    }

    aside ul li {
        margin-bottom: 10px;
    }

    aside ul li a {
        color: white;
        text-decoration: none;
        font-size: 1rem;
        padding: 12px;
        display: block;
        background-color: #495057;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    aside ul li a:hover {
        background-color: #0056b3;
    }

    main {
        flex: 1;
        background-color: white;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    main p {
        font-size: 1rem;
        color: #666;
    }

    footer {
        text-align: center;
        font-size: 0.85rem;
        color: #666;
        padding: 15px;
        background-color: #f4f7f9;
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
                    <li><a href="?page=orders">Danh sách đơn hàng</a></li>
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
                    case "orders":
                        require_once 'orders.php';
                        break;
                    case "orders_detail":
                        require_once 'orders_detail.php';
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
