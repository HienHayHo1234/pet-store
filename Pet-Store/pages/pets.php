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



<div class="loc">
    <form id="filterForm" method="GET" action="">
        <div class="filter-group">
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
        <div class="filter-group">
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
        <div class="filter-group">
            <button type="submit">Lọc</button>
        </div>
    </form>
</div>

<style>
   .loc {
    background-color: rgb(255, 250, 245);
    padding: 20px; /* Thêm padding để không gian quanh form */
    display: flex; /* Sử dụng flexbox để căn chỉnh form */
    justify-content: flex-end; /* Căn chỉnh form sang bên phải */
}

#filterForm {
    border: 1px solid #ddd;
    border-radius: 8px;
    width: 670px; /* Đảm bảo form không quá rộng */
    background-color: #f9f9f9; /* Nền màu sáng cho form */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tạo bóng cho form */
    display: flex; /* Sử dụng flexbox để xếp ngang */
    gap: 10px; /* Khoảng cách giữa các nhóm lọc */
    align-items: center; /* Căn giữa các phần tử theo chiều dọc */
    margin-right: 5%; /* Khoảng cách bên phải */
    overflow: auto; /* Đảm bảo form có thanh cuộn nếu cần */
    max-height: 100%; /* Đảm bảo form không cao quá trang */
}

.filter-group {
    display: flex; /* Sử dụng flexbox để căn chỉnh các phần tử trong nhóm */
    align-items: center; /* Căn giữa theo chiều dọc */
    gap: 10px; /* Khoảng cách giữa nhãn và ô chọn */
    flex: 1 1 calc(45% - 10px); /* Chiếm khoảng 45% chiều rộng trừ khoảng cách */
}

.filter-group label {
    margin: 0; /* Loại bỏ khoảng cách dưới label */
    white-space: nowrap; /* Ngăn ngừa việc label bị cắt dòng */
    color: #333; /* Màu chữ cho nhãn */
}

.filter-group select {
    width: auto; /* Để tự động điều chỉnh kích thước theo nội dung */
    flex: 1; /* Chiếm phần còn lại của không gian */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fff; /* Nền trắng cho dropdown */
    box-sizing: border-box; /* Đảm bảo padding không vượt quá chiều rộng */
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
    transition: background-color 0.3s; /* Hiệu ứng chuyển màu nền */
    margin-top: 10px; /* Khoảng cách trên nút */
    margin-bottom: 10px;
}

.filter-group button[type="submit"]:hover {
    background-color: #0056b3;
}

</style>







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

