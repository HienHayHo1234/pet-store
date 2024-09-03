<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Giỏ Hàng</title>
</head>
<body>
    <div class="container mt-5">
        <h4 class="text-center">Danh Sách Sản Phẩm Trong Giỏ Hàng</h4>

        <?php
        // Kết nối cơ sở dữ liệu
        require_once 'functions.php';

        // Lấy danh sách sản phẩm trong giỏ hàng của người dùng
        $userId = $_SESSION['login_id']; // ID người dùng từ session

        // Cập nhật câu lệnh SQL theo cấu trúc bảng mới
        $sql = "SELECT p.id, p.name, p.idLoai AS type, p.price, c.quantity 
                FROM cart_items c
                JOIN pets p ON c.pet_id = p.id
                WHERE c.user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        // Hiển thị thông báo nếu giỏ hàng trống
        if ($stmt->rowCount() == 0) {
            echo '<div class="alert alert-info">Giỏ hàng của bạn hiện đang trống.</div>';
        } else {
            // Hiển thị bảng danh sách sản phẩm trong giỏ hàng
            echo '<table class="table table-bordered">';
            echo '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                    </tr>
                  </thead>';
            echo '<tbody>';

            $totalPrice = 0;
            while ($row = $stmt->fetch()) {
                $total = $row['price'] * $row['quantity'];
                $totalPrice += $total;
                echo '<tr>
                        <td>' . htmlspecialchars($row['id']) . '</td>
                        <td>' . htmlspecialchars($row['name']) . '</td>
                        <td>' . htmlspecialchars($row['type']) . '</td>
                        <td>' . number_format($row['price'], 2) . ' VND</td>
                        <td>' . htmlspecialchars($row['quantity']) . '</td>
                        <td>' . number_format($total, 2) . ' VND</td>
                      </tr>';
            }

            echo '</tbody>';
            echo '<tfoot>
                    <tr>
                        <td colspan="5" class="text-right font-weight-bold">Tổng tiền:</td>
                        <td class="font-weight-bold">' . number_format($totalPrice, 2) . ' VND</td>
                    </tr>
                  </tfoot>';
            echo '</table>';
        }
        ?>
    </div>
</body>
</html>
