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

// Xử lý form đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "Vui lòng nhập đầy đủ thông tin.";
    } elseif ($password !== $confirmPassword) {
        echo "Mật khẩu xác nhận không khớp.";
    } else {
        // Kiểm tra nếu người dùng đã tồn tại
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $checkUser->bindParam(':username', $username);
        $checkUser->bindParam(':email', $email);
        $checkUser->execute();

        if ($checkUser->rowCount() > 0) {
            echo "Tài khoản đã tồn tại. Vui lòng <a href='login.php'>đăng nhập</a>.";
        } else {
            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Thêm người dùng vào cơ sở dữ liệu
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                echo "Đăng ký thành công!";
                header("Location: login.php");
                exit();
            } else {
                echo "Đã xảy ra lỗi khi đăng ký.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Đăng Ký</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../asset/css/register.css" rel="stylesheet"> <!-- Đường dẫn tới file CSS -->
</head>

<body>
    <div class="container">
        <h2>Đăng Ký</h2>
        <form action="register.php" method="post">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="confirmPassword">Confirm Password</label><br>
            <input type="password" id="confirmPassword" name="confirmPassword" required><br>
            <div class="button-container">
                <button type="submit">Submit</button>
                <button type="reset">Clear</button>
            </div>
            <p>Or</p>
            <a href="index.php"><img src="../asset/images/icon/logo.png" alt="Logo"></a> <!-- Đường dẫn tới logo -->
        </form>
    </div>
</body>

</html>

