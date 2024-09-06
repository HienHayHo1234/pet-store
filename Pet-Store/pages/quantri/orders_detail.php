<?php
// Kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$userdb = "root";
$passdb = "";
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $userdb, $passdb);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Hàm lấy chi tiết đơn hàng
function layChiTietDonHang($order_id) {
    global $conn;
    // Truy vấn để lấy chi tiết đơn hàng
    $sql = "SELECT o.idOrder AS order_id, o.orderDate, o.status AS order_status, 
                   p.name AS pet_name, od.quantity, od.price, (od.quantity * od.price) AS item_total
            FROM orders o
            JOIN order_details od ON o.idOrder = od.order_id
            JOIN pets p ON od.pet_id = p.id
            WHERE o.idOrder = ?
            ORDER BY p.name";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy mã đơn hàng từ URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Lấy chi tiết đơn hàng
$details = layChiTietDonHang($order_id);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
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
            margin-right: 0;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        .table td img {
            width: 100px;
            border-radius: 5px;
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

        .btn-back {
            background-color: #5bc0de;
        }

        .btn-back:hover {
            background-color: #31b0d5;
        }
    </style>
</head>
<body>
    <h4>Chi Tiết Đơn Hàng #<?= $order_id ?></h4>
    <?php if (!empty($details)) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Tổng giá</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_order_price = 0;
                foreach ($details as $detail) {
                    $total_order_price += $detail['item_total'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($detail['pet_name']) ?></td>
                        <td><?= $detail['quantity'] ?></td>
                        <td><?= number_format($detail['price'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($detail['item_total'], 0, ',', '.') ?> VND</td>
                    </tr>
                <?php } ?>
                <tr style="background-color: #f9f9f9;">
                    <td colspan="3" style="text-align: right; font-weight: bold;">Tổng cộng</td>
                    <td><?= number_format($total_order_price, 0, ',', '.') ?> VND</td>
                </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <p>Không có thông tin chi tiết cho đơn hàng này.</p>
    <?php } ?>
    <div style="text-align: center; margin: 20px;">
        <a href="index.php?page=orders" class="btn btn-back">Trở về</a>
    </div>
</body>
</html>
