<?php
require_once 'functions.php';

// Lấy id của thú cưng cần chỉnh sửa từ URL
$id = $_GET['id'] ?? '';
$id = htmlspecialchars($id);

// Lấy thông tin chi tiết của thú cưng từ cơ sở dữ liệu
$pet = layChiTietPets($id); // Bạn cần tạo hàm `layChiTietPet` trong file `functions.php`

// Xử lý khi form được gửi
if (isset($_POST['btn'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $priceSale = $_POST['priceSale'] ?? null;
    $gender = $_POST['gender'];
    $quantity = $_POST['quantity'];
    $urlImg = $_POST['urlImg'];
    $idLoai = $_POST['idLoai'];
    $description = $_POST['description'];
    settype($price, "float");
    settype($priceSale, "float");
    settype($gender, "int");
    settype($quantity, "int");
    settype($hot, "int");

    // Cập nhật thông tin thú cưng trong cơ sở dữ liệu
    $kq = capnhatPets($id, $name, $price, $priceSale, $gender, $quantity, $urlImg, $idLoai, $description, $hot);

    if ($kq) {
        header("location: index.php?page=pets_ds");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thú cưng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h4 class="col-10 m-auto p-2 text-center">CHỈNH SỬA THÚ CƯNG</h4>
    <form action="" method="post" class="border border-primary col-10 m-auto p-2">
        <div class="form-group">
            <label>Tên thú cưng</label>
            <input name="name" type="text" class="form-control" value="<?= htmlspecialchars($pet['name']) ?>" />
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input name="price" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($pet['price']) ?>" />
        </div>
        <div class="form-group">
            <label>Giá khuyến mãi</label>
            <input name="priceSale" type="number" step="0.01" class="form-control" value="<?= htmlspecialchars($pet['priceSale']) ?>" />
        </div>
        <div class="form-group">
            <label>Giới tính:</label>
            <input name="gender" type="radio" value="1" <?= $pet['gender'] == 1 ? 'checked' : '' ?> /> Đực
            <input name="gender" type="radio" value="0" <?= $pet['gender'] == 0 ? 'checked' : '' ?> /> Cái
        </div>
        <div class="form-group">
            <label>Số lượng</label>
            <input name="quantity" type="number" class="form-control" value="<?= htmlspecialchars($pet['quantity']) ?>" />
        </div>
        <div class="form-group">
            <label>URL Hình ảnh: </label>
            <img class="img-pet" src="<?php echo htmlspecialchars($pet['urlImg']); ?>"/>
            <input name="urlImg" type="text" class="form-control" value="<?= htmlspecialchars($pet['urlImg']) ?>" />
        </div>


        <div class="form-group">
            <label>Loại thú cưng</label>
            <input name="idLoai" type="text" class="form-control" value="<?= htmlspecialchars($pet['idLoai']) ?>" />
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($pet['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Hot</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($pet['hot']) ?></textarea>
        </div>
        <div class="form-group">
            <input name="btn" type="submit" value="Lưu thông tin" class="btn btn-primary" />
        </div>

    </form>
</body>
</html>
