<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANH SÁCH ĐƠN HÀNG</title>
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

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin: 20px 0;
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle; /* Căn giữa nội dung theo chiều dọc */
        }

        .table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            margin: 0 5px;
        }

        .btn-view {
            background-color: #5bc0de;
        }

        .btn-view:hover {
            background-color: #31b0d5;
        }

        .btn-edit {
            background-color: #f0ad4e;
        }

        .btn-edit:hover {
            background-color: #ec971f;
        }

        .btn-delete {
            background-color: #d9534f;
        }

        .btn-delete:hover {
            background-color: #c9302c;
        }

    </style>
</head>
<body>
    <h4>DANH SÁCH ĐƠN HÀNG</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Ngày Đặt</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Chi Tiết</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'functions.php';
            $listOrders = layDanhSachDonHang(); // Gọi hàm lấy danh sách đơn hàng
            foreach ($listOrders as $row) {
            ?>
            <tr>
                <td><?= $row['order_id'] ?></td>
                <td><?= $row['orderDate'] ?></td>
                <td><?= number_format($row['total_price'], 0, ',', '.') ?> VND</td>
                <td><?= htmlspecialchars($row['order_status']) ?></td>
                <td><a href="orders_detail.php?order_id=<?= $row['order_id'] ?>" class="btn btn-view">Xem Chi Tiết</a></td>
                <td>
                    <a href="orders_sua.php?order_id=<?= $row['order_id'] ?>" class="btn btn-edit">Sửa</a>
                    <a href="orders_xoa.php?order_id=<?= $row['order_id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
