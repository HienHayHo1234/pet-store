<?php
// Kết nối đến cơ sở dữ liệu
require_once 'functions.php'; // Đảm bảo đường dẫn chính xác

// Lấy danh sách các sản phẩm thú cưng từ cơ sở dữ liệu
$petsList = layDanhSachPets(); // Gán kết quả vào biến
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Mục Thú Cưng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="update_pet.php">
</head>
<body>
    <div class="container mt-5">
        <h4 class="text-center">Danh Mục Thú Cưng</h4>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Loại</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($petsList) && !empty($petsList)): ?>
                    <?php foreach ($petsList as $pet): ?>
                        <tr>
                            <td><?= htmlspecialchars($pet['id']) ?></td>
                            <td><?= htmlspecialchars($pet['name']) ?></td>
                            <td><?= htmlspecialchars($pet['idLoai']) ?></td>
                            <td><?= htmlspecialchars($pet['price']) ?></td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
