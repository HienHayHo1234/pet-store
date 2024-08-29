<?php

// Hiển thị thông báo lỗi nếu có
if (isset($_SESSION['thongbao'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['thongbao'] . '</div>';
    unset($_SESSION['thongbao']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh Mục Thú Cưng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h4 class="text-center">Thêm Danh Mục Thú Cưng</h4>

        <form action="" method="post" class="border border-primary col-6 m-auto p-3 mt-4">
            <div class="form-group">
                <label for="idLoai">ID Loại</label>
                <input name="idLoai" id="idLoai" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tenLoai">Tên Loại</label>
                <input name="tenLoai" id="tenLoai" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="soTT">Số Thứ Tự</label>
                <input name="soTT" id="soTT" type="number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="anHien">Ẩn/Hiện</label>
                <select name="anHien" id="anHien" class="form-control">
                    <option value="1">Hiện</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            <div class="form-group">
                <input name="btn" type="submit" value="Thêm" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['btn'])) {
    // Tiếp nhận dữ liệu từ form
    $idLoai = trim(strip_tags($_POST['idLoai'])); // Không cần ép kiểu, xử lý đầu vào văn bản
    $tenLoai = trim(strip_tags($_POST['tenLoai']));
    $soTT = (int)$_POST['soTT']; // Ép kiểu thành số nguyên
    $anHien = (int)$_POST['anHien']; // Ép kiểu thành số nguyên

    // Kết nối cơ sở dữ liệu
    require_once 'functions.php';

    // Chèn dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO danhmucpets (idLoai, tenLoai, soTT, anHien) VALUES (:idLoai, :tenLoai, :soTT, :anHien)";
    $stmt = $conn->prepare($sql);
    
    $result = $stmt->execute([
        ':idLoai' => $idLoai,
        ':tenLoai' => $tenLoai,
        ':soTT' => $soTT,
        ':anHien' => $anHien
    ]);

    if ($result) {
        header("Location: index.php?page=danhmucpets_ds"); // Chuyển hướng đến danh sách danh mục thú cưng
        exit();
    } else {
        $_SESSION['thongbao'] = "Lỗi khi thêm danh mục thú cưng. Vui lòng thử lại.";
        header("Location: danhmucpets_them.php"); // Quay lại trang thêm
        exit();
    }
}
?>
