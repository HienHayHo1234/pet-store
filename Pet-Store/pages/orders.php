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

// Truy vấn đơn hàng từ bảng cart_items với JOIN bảng pets
$user_id = 1; // Thay đổi ID người dùng tùy theo yêu cầu
$sql = "SELECT ci.id, ci.quantity, ci.created_at, p.name AS pet_name, p.price AS pet_price
        FROM cart_items ci
        JOIN pets p ON ci.pet_id = p.id
        WHERE ci.user_id = $user_id";
$result = $conn->query($sql);
?>

<div class="content">
    <h2>Đơn hàng của bạn</h2>
    <?php
    if ($result->num_rows > 0) {
        $total_cart_price = 0; // Biến để lưu tổng tiền của giỏ hàng

        // Hiển thị danh sách đơn hàng với CSS để tạo đường kẻ bảng
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='border: 1px solid black; background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid black; padding: 8px;'>ID</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Tên Thú Cưng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Số lượng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Tổng tiền</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Ngày đặt</th>";
        echo "</tr>";
        while($row = $result->fetch_assoc()) {
            $item_total_price = $row["quantity"] * $row["pet_price"];
            $total_cart_price += $item_total_price; // Cộng dồn tổng tiền của giỏ hàng
            
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["pet_name"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["quantity"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . number_format($item_total_price, 0, ',', '.') . " VND</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["created_at"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Hiển thị tổng tiền của toàn bộ giỏ hàng
        echo "<p><strong>Thành tiền: </strong>" . number_format($total_cart_price, 0, ',', '.') . " VND</p>";
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>
