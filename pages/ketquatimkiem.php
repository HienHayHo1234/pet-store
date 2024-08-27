<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pet-store"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu từ khóa tìm kiếm được gửi đi
if (isset($_GET['tukhoa'])) {
    $tukhoa = $_GET['tukhoa'];

    // Truy vấn tìm kiếm dựa trên từ khóa sử dụng prepared statement để tránh SQL injection
    $stmt = $conn->prepare("SELECT * FROM pets WHERE name LIKE ? OR id LIKE ?");
    $likeKeyword = "%$tukhoa%";
    $stmt->bind_param("ss", $likeKeyword, $likeKeyword);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hiển thị tiêu đề kết quả tìm kiếm
    echo "<h2>Kết quả tìm kiếm cho từ khóa: '" . htmlspecialchars($tukhoa, ENT_QUOTES, 'UTF-8') . "'</h2>";

    if ($result->num_rows > 0) {
        echo '<div class="pets-grid">'; // Bắt đầu bao ngoài cho tất cả các sản phẩm
        while ($row = $result->fetch_assoc()) {
            // Gọi hàm để hiển thị sản phẩm
            displayPet(
                $row['urlImg'],
                $row['name'],
                $row['id'],
                $row['price'],
                $row['priceSale']
            );
        }
        echo '</div>'; // Kết thúc lớp bao ngoài
    } else {
        echo "<p>Không tìm thấy kết quả nào.</p>";
    }

    $stmt->close(); // Đóng prepared statement
} else {
    echo "<p>Vui lòng nhập từ khóa để tìm kiếm.</p>";
}

$conn->close();
?>

<?php
// Hàm hiển thị sản phẩm
function displayPet($urlImg, $name, $id, $price, $priceSale) {
    ?>
    <div class="pet-item">
        <div class="container-pets">
            <img src="<?php echo htmlspecialchars($urlImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="row">
                <p class="name-pet"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="icons">
                    <button class="heart" onclick="toggleHeart(this)">❤<i class="fas fa-heart"></i></button>
                    <button class="button view-detail" onclick="openModal('<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>')">Chi tiết</button>
                    <button class="button order" onclick="addToPet('<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>')">Giỏ hàng</button>
                </div>
            </div>
            <p class="text-price">Giá: <span class="price"><?php echo number_format($price, 0, ',', '.'); ?>đ</span>
                <?php if (!empty($priceSale)) { ?>
                    ➱ <?php echo number_format($priceSale, 0, ',', '.'); ?>đ
                <?php } ?>
            </p>
        </div>
    </div>
    <?php
}
?>
