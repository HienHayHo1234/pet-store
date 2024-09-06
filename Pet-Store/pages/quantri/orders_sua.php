<?php
require_once 'functions.php';

// Lấy id của đơn hàng cần chỉnh sửa từ URL
$id = $_GET['id'] ?? '';
$id = htmlspecialchars($id);

// Lấy thông tin chi tiết của đơn hàng từ cơ sở dữ liệu
$order = layChiTietDonHang($id); // Bạn cần tạo hàm `layChiTietDonHang` trong file `functions.php`

// Xử lý khi form được gửi
if (isset($_POST['btn'])) {
    $status = $_POST['status'];
    
    // Cập nhật thông tin đơn hàng trong cơ sở dữ liệu
    $kq = capnhatDonHang($id, $status);

    if ($kq) {
        header("location: index.php?page=orders");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h4 {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            font-size: 24px;
            color: #333;
            background-color: #f0f0f0;
            border-radius: 8px;
        }

        form {
            width: 40%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 10px 15px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <h4>CHỈNH SỬA ĐƠN HÀNG</h4>
    <form action="" method="post">
        <div class="form-group">
            <label for="order_id">Mã Đơn Hàng</label>
            <input name="order_id" type="text" id="order_id" value="<?= htmlspecialchars($order['order_id']) ?>" readonly />
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" id="status">
                <option value="Chờ xử lý" <?= $order['status'] == 'Chờ xử lý' ? 'selected' : '' ?>>Chờ xử lý</option>
                <option value="Đang xử lý" <?= $order['status'] == 'Đang xử lý' ? 'selected' : '' ?>>Đang xử lý</option>
                <option value="Hoàn thành" <?= $order['status'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                <option value="Đã hủy" <?= $order['status'] == 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
            </select>
        </div>
        <div class="form-group btn-group">
            <input name="btn" type="submit" value="Lưu thông tin" class="btn btn-primary" />
            <a href="index.php?page=orders" class="btn btn-secondary">Quay lại</a>
        </div>
    </form>
</body>
</html>
