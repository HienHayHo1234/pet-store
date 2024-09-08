<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
require '../config/config.php';

try {
    // Tạo đối tượng PDO để kết nối với cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Thực hiện truy vấn để lấy tất cả các sản phẩm thuộc danh mục 'cat'
    $stmt = $conn->prepare("SELECT * FROM pets");

    $stmt->execute();

    // Lưu kết quả truy vấn vào một mảng
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
require '../config/config.php';

try {
    // Tạo đối tượng PDO để kết nối với cơ sở dữ liệu MySQL
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Khởi tạo mảng các điều kiện
    $conditions = [];
    $params = [];

    // Lọc theo tên sản phẩm
    if (!empty($_GET['name'])) {
        $conditions[] = 'name LIKE :name';
        $params[':name'] = '%' . $_GET['name'] . '%';
    }

    // Lọc theo giá
    if (!empty($_GET['min_price'])) {
        $conditions[] = 'price >= :min_price';
        $params[':min_price'] = $_GET['min_price'];
    }
    if (!empty($_GET['max_price'])) {
        $conditions[] = 'price <= :max_price';
        $params[':max_price'] = $_GET['max_price'];
    }

    // Xây dựng câu truy vấn SQL
    $sql = "SELECT * FROM pets";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Lưu kết quả truy vấn vào một mảng
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


<form method="GET" action="">

    <div>
        <label for="min_price">Giá tối thiểu:</label>
        <select id="min_price" name="min_price">
            <option value="">--Chọn giá tối thiểu--</option>
            <?php for ($i = 0; $i <= 10; $i++): ?>
                <?php $value = $i * 1000000; ?>
                <option value="<?php echo $value; ?>" <?php echo isset($_GET['min_price']) && $_GET['min_price'] == $value ? 'selected' : ''; ?>>
                    <?php echo number_format($value, 0, ',', '.'); ?>đ
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div>
        <label for="max_price">Giá tối đa:</label>
        <select id="max_price" name="max_price">
            <option value="">--Chọn giá tối đa--</option>
            <?php for ($i = 0; $i <= 10; $i++): ?>
                <?php $value = $i * 1000000; ?>
                <option value="<?php echo $value; ?>" <?php echo isset($_GET['max_price']) && $_GET['max_price'] == $value ? 'selected' : ''; ?>>
                    <?php echo number_format($value, 0, ',', '.'); ?>đ
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <button type="submit">Lọc</button>
</form>


<div class="pets-grid">
    <?php if (!empty($pets)): ?>
        <?php foreach ($pets as $pet): ?>
            <div class="container-pets">
                <img src="<?php echo htmlspecialchars($pet['urlImg']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>">
                <div class="row">
                    <p class="name-pet"><?php echo htmlspecialchars($pet['name']); ?></p>
                    <div class="icons">
                        <button class="button view-details" data-id="<?php echo htmlspecialchars($pet['id']); ?>">Chi tiết</button>
                        <button class="button order"
                            onclick="addToPet('<?php echo htmlspecialchars($pet['id'], ENT_QUOTES, 'UTF-8'); ?>')">Giỏ
                            hàng</button>
                    </div>  
                </div>
                <p class="text-price">Giá: <span class="price"><?php echo number_format($pet['price'], 0, ',', '.'); ?>đ</span>
                    ➱
                    <?php echo number_format($pet['priceSale'], 0, ',', '.'); ?>đ</p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có sản phẩm nào.</p>
    <?php endif; ?>
</div>
<style>
/* CSS cho form tìm kiếm */
#filterForm {
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    max-width: 600px; /* Đảm bảo form không quá rộng */
}

.filter-group {
    margin-bottom: 15px;
    z-index: 10001;

}

.filter-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.filter-group input[type="text"],
.filter-group input[type="number"] {
    width: calc(100% - 20px); /* Chiếm 100% chiều rộng của div */
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.filter-group button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    display: block;
    text-align: center;
}

.filter-group button[type="submit"]:hover {
    background-color: #0056b3;
}

</style>