<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>THÊM THÚ CƯNG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        h4 {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            font-size: 24px;
            color: #333;
            background-color: #f0f0f0;
            border-radius: 8px;
        }

        form {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="radio"] {
            margin-right: 10px;
        }

        .form-group textarea {
            height: 100px;
        }

        .form-group .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group .btn:hover {
            background-color: #218838;
        }

        .form-group input[type="submit"] {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h4>THÊM THÚ CƯNG</h4>
    <form action="" method="post">
        <div class="form-group">
            <label>Tên thú cưng</label>
            <input name="name" type="text" required />
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input name="price" type="number" step="0.01" required />
        </div>
        <div class="form-group">
            <label>Giá khuyến mãi</label>
            <input name="priceSale" type="number" step="0.01" />
        </div>
        <div class="form-group">
            <label>Giới tính:</label>
            <input name="gender" type="radio" value="1" required /> Đực
            <input name="gender" type="radio" value="0" /> Cái
        </div>
        <div class="form-group">
            <label>Số lượng</label>
            <input name="quantity" type="number" required />
        </div>
        <div class="form-group">
            <label for="imageUpload">Chọn hình ảnh</label>
            <input name="urlImg" type="file" id="imageUpload" accept="image/*" required />
        </div>

        <div class="form-group">
            <label>Danh mục</label>
            <input name="idLoai" type="text" required />
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description"></textarea>
        </div>
        <div class="form-group">
            <label>Nổi bật:</label>
            <input name="hot" type="radio" value="1" /> Có
            <input name="hot" type="radio" value="0" checked /> Không
        </div>
        <div class="form-group">
            <input name="btn" type="submit" value="Thêm" class="btn" />
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
