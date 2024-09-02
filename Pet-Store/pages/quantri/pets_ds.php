<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>DANH SÁCH THÚ CƯNG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <h4 class="col-10 m-auto p-2 text-center">DANH SÁCH THÚ CƯNG</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Tên Thú Cưng</th>
                <th>Giá</th>
                <th>Giá Khuyến Mãi</th>
                <th>Giới Tính</th>
                <th>Số Lượng</th>
                <th>Hình Ảnh</th>
                <th>Danh Mục</th>
                <th>Hot</th>
                <th>Hành động</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'functions.php';
            $listPets = layDanhSachPets(); // Gọi hàm lấy danh sách thú cưng
            foreach ($listPets as $row) {
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= number_format($row['price'], 0, ',', '.') ?> VND</td>
                <td><?= $row['priceSale'] ? number_format($row['priceSale'], 0, ',', '.') . ' VND' : 'Không có' ?></td>
                <td><?= $row['gender'] == 1 ? 'Đực' : 'Cái' ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><img src="<?= $row['urlImg'] ?>" alt="<?= $row['name'] ?>" style="width: 100px;"></td>
                <td><?= $row['idLoai'] ?></td>
                <td><?= $row['hot'] == 1 ? 'Hot' : 'Null' ?></td>
                <td>
                    <a href="pets_sua.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="pets_xoa.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
