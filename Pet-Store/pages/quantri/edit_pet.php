<?php


// Kết nối đến cơ sở dữ liệu
require_once 'functions.php'; // Đảm bảo đường dẫn chính xác

// Kiểm tra nếu ID thú cưng đã được truyền qua URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['thongbao'] = "ID không hợp lệ.";
    header("Location: index.php?page=danhmucpets_ds");
    exit();
}

$id = (int)$_GET['id'];

// Lấy thông tin thú cưng từ cơ sở dữ liệu
$petsList = layDanhSachPets(); // Sử dụng hàm lấy danh sách thú cưng
$pet = null;
foreach ($petsList as $p) {
    if ($p['id'] == $id) {
        $pet = $p;
        break;
    }
}

if (!$pet) {
    $_SESSION['thongbao'] = "Không tìm thấy thú cưng.";
    header("Location: index.php?page=danhmucpets_ds");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thú Cưng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        // Hiển thị thông báo lỗi nếu có
        if (isset($_SESSION['thongbao'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['thongbao'] . '</div>';
            unset($_SESSION['thongbao']);
        }
        ?>

        <h4 class="text-center">Sửa Thú Cưng</h4>

        <form action="update_pet.php" method="post" class="border border-primary col-6 m-auto p-3 mt-4">
            <input type="hidden" name="id" value="<?= htmlspecialchars($pet['id']) ?>">
            <div class="form-group">
                <label for="name">Tên</label>
                <input name="name" id="name" type="text" class="form-control" value="<?= htmlspecialchars($pet['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Loại</label>
                <input name="type" id="type" type="text" class="form-control" value="<?= htmlspecialchars($pet['idLoai']) ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Giá</label>
                <input name="price" id="price" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($pet['price']) ?>" required>
            </div>
            <div class="form-group">
                <input name="btn" type="submit" value="Cập Nhật" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
