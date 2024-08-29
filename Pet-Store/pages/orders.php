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

// Truy vấn đơn hàng từ bảng cart_items
$user_id = 1; // Thay đổi ID người dùng tùy theo yêu cầu
$sql = "SELECT id, user_id, pet_id, quantity, created_at FROM cart_items WHERE user_id = $user_id";
$result = $conn->query($sql);
?>

<div class="content">
    <h2>Đơn hàng của bạn</h2>
    <?php
    if ($result->num_rows > 0) {
        // Hiển thị danh sách đơn hàng với CSS để tạo đường kẻ bảng
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='border: 1px solid black; background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid black; padding: 8px;'>ID</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Pet ID</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Số lượng</th>";
        echo "<th style='border: 1px solid black; padding: 8px;'>Ngày đặt</th>";
        echo "</tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["pet_id"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["quantity"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["created_at"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Không có đơn hàng nào.</p>";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>
