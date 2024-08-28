<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>THÊM THÚ CƯNG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <h4 class="col-10 m-auto p-2 text-center">THÊM THÚ CƯNG</h4>
    <form action="" method="post" class="border border-primary col-10 m-auto p-2">
        <div class="form-group">
            <label>Tên thú cưng</label>
            <input name="name" type="text" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input name="price" type="number" step="0.01" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Giá khuyến mãi</label>
            <input name="priceSale" type="number" step="0.01" class="form-control" />
        </div>
        <div class="form-group">
            <label>Giới tính:</label>
            <input name="gender" type="radio" value="1" required /> Đực
            <input name="gender" type="radio" value="0" /> Cái
        </div>
        <div class="form-group">
            <label>Số lượng</label>
            <input name="quantity" type="number" class="form-control" required />
        </div>
        <div class="form-group">
            <label for="imageUpload">Chọn hình ảnh</label>
            <input name="urlImg" type="file" id="imageUpload" class="form-control-file" accept="image/*" required />
        </div>

        <div class="form-group">
            <label>Danh mục</label>
            <input name="idLoai" type="text" class="form-control" required />
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Nổi bật:</label>
            <input name="hot" type="radio" value="1" /> Có
            <input name="hot" type="radio" value="0" checked /> Không
        </div>
        <div class="form-group">
            <input name="btn" type="submit" value="Thêm" class="btn btn-primary" />
        </div>
    </form>
    <?php
    if (isset($_POST['btn'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $priceSale = $_POST['priceSale'];
        $gender = $_POST['gender'];
        $quantity = $_POST['quantity'];
        $urlImg = $_POST['urlImg'];
        $idLoai = $_POST['idLoai'];
        $description = $_POST['description'];
        $hot = $_POST['hot'];

        // Xử lý dữ liệu đầu vào
        $name = trim(strip_tags($name));
        settype($price, "float");
        settype($priceSale, "float");
        settype($gender, "int");
        settype($quantity, "int");
        settype($hot, "int");
        $urlImg = trim(strip_tags($urlImg));
        $idLoai = trim(strip_tags($idLoai));
        $description = trim(strip_tags($description));

        // Gọi hàm thêm thú cưng
        require_once 'functions.php';
        $kq = themPets($id,$name, $price, $priceSale, $gender, $quantity, $urlImg, $idLoai, $description, $hot);

        // Kiểm tra kết quả và chuyển hướng
        if ($kq) {
            header("location: index.php?page=pets_ds");
            exit();
        }
    }
    ?>
</body>
</html>
