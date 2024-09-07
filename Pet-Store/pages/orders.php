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

if (isset($_SESSION['username'])) {
    $login_username = $_SESSION['username'];
} else {
    die("Tên đăng nhập không được cung cấp.");
}

// Truy vấn để lấy user_id dựa trên tên đăng nhập
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login_username);
$stmt->execute();
$stmt->store_result();

// Kiểm tra nếu có kết quả và lấy user_id
if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id);
    $stmt->fetch();
} else {
    die("Tên đăng nhập không tồn tại.");
}

// Truy vấn đơn hàng từ bảng orders và chi tiết từ bảng order_details với JOIN bảng pets
$sql = "SELECT o.idOrder AS order_id, o.orderDate, o.status AS order_status, od.quantity, p.name AS pet_name, od.price AS pet_price
        FROM orders o
        JOIN order_details od ON o.idOrder = od.order_id
        JOIN pets p ON od.pet_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.idOrder";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="content">
    <h2>Đơn hàng của bạn</h2>
    <?php
    if ($result->num_rows > 0) {
        $total_cart_price = 0; // Biến để lưu tổng tiền của giỏ hàng

        // Hiển thị danh sách đơn hàng với CSS để tạo đường kẻ bảng
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<thead>";
        echo "<tr style='background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Mã đơn hàng</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Ngày mua</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Sản phẩm</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Tổng tiền</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Trạng thái đơn hàng</th>";
        echo "<th style='border: 1px solid #ddd; padding: 10px; text-align: center;'>Chi tiết</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $current_order_id = 0;
        $order_total_price = 0;

        while ($row = $result->fetch_assoc()) {
            if ($current_order_id != $row['order_id']) {
                if ($current_order_id != 0) {
                    // Hiển thị tổng tiền cho đơn hàng trước đó
                    echo "<tr style='background-color: #f9f9f9;'>";
                    echo "<td colspan='4' style='border: 1px solid #ddd; padding: 10px;'>Tổng đơn hàng</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . number_format($order_total_price, 0, ',', '.') . " VND</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 10px;'></td>";
                    echo "</tr>";

                    // Cộng tổng tiền của đơn hàng trước vào tổng tiền giỏ hàng
                    $total_cart_price += $order_total_price;    
                }

                $order_total_price = 0;
                $current_order_id = $row['order_id'];
            }

            $item_total_price = $row["quantity"] * $row["pet_price"];
            $order_total_price += $item_total_price; // Cộng dồn tổng tiền của đơn hàng

            echo "<tr>";
            echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . $row["order_id"] . "</td>"; // Mã đơn hàng
            echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . $row["orderDate"] . "</td>"; // Ngày mua
            echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . $row["pet_name"] . "</td>"; // Sản phẩm
            echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . number_format($item_total_price, 0, ',', '.') . " VND</td>"; // Tổng đơn hàng
            echo "<td style='border: 1px solid #ddd; padding: 10px; color: red;'>" . $row['order_status'] . "</td>"; // Trạng thái đơn hàng
            // Thêm nút "Chi tiết"
            echo "<td style='border: 1px solid #ddd; padding: 10px; text-align: center;'>
                    <a href='index.php?page=index_user&pageuser=orders_detail&order_id=" . $row['order_id'] . "' style='padding: 5px 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Chi tiết</a>
                  </td>";
            echo "</tr>";
        }

        // Hiển thị tổng tiền cho đơn hàng cuối cùng
        if ($current_order_id != 0) {
            echo "<tr style='background-color: #f9f9f9;'>";
            echo "<td colspan='4' style='border: 1px solid #ddd; padding: 10px;'>Tổng đơn hàng</td>";
            echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . number_format($order_total_price, 0, ',', '.') . " VND</td>";
            echo "<td style='border: 1px solid #ddd; padding: 10px;'></td>";
            echo "</tr>";

            // Cộng tổng tiền của đơn hàng cuối cùng vào tổng tiền giỏ hàng
            $total_cart_price += $order_total_price;
        }

        // Hiển thị tổng tiền của giỏ hàng
        echo "<tr style='background-color: #e0e0e0;'>";
        echo "<td colspan='4' style='border: 1px solid #ddd; padding: 10px;'>Tổng tiền giỏ hàng</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'>" . number_format($total_cart_price, 0, ',', '.') . " VND</td>";
        echo "<td style='border: 1px solid #ddd; padding: 10px;'></td>";
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
    ?>
</div>
