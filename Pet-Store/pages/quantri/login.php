<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        session_start();
        
        // Hiển thị thông báo lỗi nếu có
        if (isset($_SESSION['thongbao'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['thongbao'] . '</div>';
            unset($_SESSION['thongbao']);
        }
        ?>
        
        <form action="" method="post" class="border border-primary col-5 m-auto p-2">
            <div class="form-group">
                <label for="username">Username</label>
                <input name="u" id="username" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input name="p" id="password" type="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input name="nho" type="checkbox"> Ghi nhớ
            </div>
            <div class="form-group">
                <input name="btn" type="submit" value="Đăng nhập" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['btn'])) {
    // Tiếp nhận user và pass từ form
    $u = trim(strip_tags($_POST['u']));
    $p = trim(strip_tags($_POST['p']));

    // Kết nối cơ sở dữ liệu
    require_once("functions.php");

    // Truy vấn kiểm tra username tồn tại
    $sql = "SELECT id, username, pass, idgroup FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':username' => $u]);

    if ($stmt->rowCount() == 0) {
        $_SESSION['thongbao'] = "Username không tồn tại";
        header("location: login.php");
        exit();
    }

    // Lấy thông tin người dùng
    $row_user = $stmt->fetch();

    // Hiển thị mật khẩu hash lên console (chỉ để mục đích kiểm tra, không nên làm ở môi trường sản xuất)


    // Kiểm tra mật khẩu
    if (!password_verify($p, $row_user['pass'])) {
        $_SESSION['thongbao'] = "Mật khẩu không đúng";
        header("location: login.php");
        exit();
    }

    // Đăng nhập thành công
    $_SESSION['login_id'] = $row_user['id'];
    $_SESSION['login_user'] = $row_user['username'];
    $_SESSION['login_group'] = $row_user['idgroup'];

    header("location: index.php"); // Chuyển đến trang chủ admin
    exit();
}
?>
