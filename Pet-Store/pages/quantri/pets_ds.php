<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANH SÁCH THÚ CƯNG</title>
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

        .table {
            width: 100%;
            margin-right: 0;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle; /* Căn giữa nội dung theo chiều dọc */
        }

        .table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        .table td img {
            width: 100px;
            border-radius: 5px;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
            margin: 0 5px;
        }

        .btn-edit {
            background-color: #f0ad4e;
        }

        .btn-edit:hover {
            background-color: #ec971f;
        }

        .btn-delete {
            background-color: #d9534f;
        }

        .btn-delete:hover {
            background-color: #c9302c;
        }

    </style>
</head>
<body>
    <h4>DANH SÁCH THÚ CƯNG</h4>
    <table class="table">
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
                <th>Mô Tả</th> <!-- Thêm cột Mô Tả -->
                <th>Hành Động</th>
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
                <td><img src="<?= $row['urlImg'] ?>" alt="<?= $row['name'] ?>"></td>
                <td><?= $row['idLoai'] ?></td>
                <td><?= $row['hot'] == 1 ? 'Hot' : 'Không' ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td> <!-- Hiển thị mô tả -->
                <td>
                    <a href="pets_sua.php?id=<?= $row['id'] ?>" class="btn btn-edit">Sửa</a>
                    <a href="pets_xoa.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
