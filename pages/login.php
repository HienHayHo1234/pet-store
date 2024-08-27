<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
    } else {
        // Kiểm tra thông tin đăng nhập
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            header("Location: index.php"); 
            // Cung cấp đường dẫn chính xác đến trang bạn muốn chuyển hướng
            exit();
        } else {
            echo "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    }
}
?>
<?php
// Khai báo các thông số kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Xử lý form đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($password)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
    } else {
        // Kiểm tra thông tin đăng nhập
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Đăng nhập thành công
            header("http://localhost/LT_WEB/Pet-Store/pages/index.php"); // Cung cấp đường dẫn chính xác đến trang bạn muốn chuyển hướng
            exit();
        } else {
            echo "Tên đăng nhập hoặc mật khẩu không đúng.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../asset/css/login.css">
</head>
<body>
    <div class="container">
        <h2>Đăng Nhập</h2> <!-- Tiêu đề thêm vào đây -->
        <form action="login.php" method="post">
            <label for="username">User Name:</label><br>
            <input type="text" id="username" name="username"><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>
            <div>
                <label><input name="status" type="checkbox"> Remember me</label>
            </div>
            <hr>
            <div class="button-container">
                <input type="submit" value="Login">
                <button type="reset">Clear</button>
            </div>
            <div class="logo-bottom">
                <a href="index.php">
                    <img src="../asset/images/icon/logo.png" alt="Logo Cửa Hàng Thú Cưng">
                </a>
            </div>
            <p>Chưa có tài khoản?<a href="register.php">Đăng ký</a></p>
        </form>
    </div>
    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            if (username === '' || password === '') {
                alert('Please fill out both fields.');
                return false;
            }
            return true;
        }

        document.querySelector('form').onsubmit = function() {
            return validateForm();
        };
    </script>
</body>
<script src="../asset/js/load.js"></script>
</html>

