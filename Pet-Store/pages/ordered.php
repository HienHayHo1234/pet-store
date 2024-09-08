<?php
// Kết nối đến cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

// Tạo kết nối
$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn đơn hàng từ bảng order_items với JOIN bảng pets
$user_id = 1; // Thay đổi ID người dùng tùy theo yêu cầu
$sql = "SELECT oi.order_id, oi.quantity, oi.price, oi.created_at, p.name AS pet_name
        FROM order_items oi
        JOIN pets p ON oi.pet_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.user_id = $user_id";
$result = $conn->query($sql);
?>

<div class="content">
    <h2>Đơn hàng đã mua</h2>
    <?php
    if ($result->num_rows > 0) {
        $total_order_price = 0; // Biến để lưu tổng tiền của đơn hàng

        // Hiển thị danh sách đơn hàng đã mua với CSS để tạo đường kẻ bảng
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='border: 1px solid black; background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid black; padding: 8px;'>ID Đơn hàng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Tên Thú Cưng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Số lượng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Tổng tiền</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Ngày mua</th>";
        echo "</tr>";
        while($row = $result->fetch_assoc()) {
            $item_total_price = $row["quantity"] * $row["price"];
            $total_order_price += $item_total_price; // Cộng dồn tổng tiền của đơn hàng
            
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["order_id"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["pet_name"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["quantity"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . number_format($item_total_price, 0, ',', '.') . " VND</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["created_at"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Hiển thị tổng tiền của toàn bộ đơn hàng
        echo "<p><strong>Thành tiền: </strong>" . number_format($total_order_price, 0, ',', '.') . " VND</p>";
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>
