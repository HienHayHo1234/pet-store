<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
require '../config/config.php';

try {
    // Tạo đối tượng PDO để kết nối với cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra nếu từ khóa tìm kiếm được gửi đi
    if (isset($_GET['tukhoa'])) {
        $tukhoa = $_GET['tukhoa'];

        // Truy vấn tìm kiếm dựa trên từ khóa sử dụng prepared statement để tránh SQL injection
        $stmt = $conn->prepare("SELECT * FROM pets WHERE name LIKE :keyword OR id LIKE :keyword");
        $likeKeyword = "%" . $tukhoa . "%";
        $stmt->bindParam(':keyword', $likeKeyword, PDO::PARAM_STR);
        $stmt->execute();

        // Lưu kết quả truy vấn vào một mảng
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}
?>

<?php if (isset($tukhoa)): ?>
    <h2>Kết quả tìm kiếm cho từ khóa: '<?php echo htmlspecialchars($tukhoa, ENT_QUOTES, 'UTF-8'); ?>'</h2>
<?php endif; ?>

<div class="pets-grid">
    <?php if (!empty($pets)): ?>
        <?php foreach ($pets as $pet): ?>
            <div class="container-pets">
                <img src="<?php echo htmlspecialchars($pet['urlImg'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="row">
                    <p class="name-pet"><?php echo htmlspecialchars($pet['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="icons">
                        <button class="view-details" data-id="<?php echo htmlspecialchars($pet['id'], ENT_QUOTES, 'UTF-8'); ?>">Chi tiết</button>
                        <button class="button order"
                            onclick="addToPet('<?php echo htmlspecialchars($pet['id'], ENT_QUOTES, 'UTF-8'); ?>')">Giỏ
                            hàng</button>
                    </div>  
                </div>
                <p class="text-price">Giá: <span class="price"><?php echo number_format($pet['price'], 0, ',', '.'); ?>đ</span>
                    <?php if (!empty($pet['priceSale'])): ?>
                        ➱ <?php echo number_format($pet['priceSale'], 0, ',', '.'); ?>đ
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Không tìm thấy kết quả nào.</p>
    <?php endif; ?>
</div>
