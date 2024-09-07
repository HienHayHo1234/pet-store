<?php
session_start();

// Kiểm tra trạng thái đăng nhập
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<link rel="stylesheet" href="../asset/css/index.css">
<link rel="stylesheet" href="../asset/css/banner.css">
<link rel="stylesheet" href="../asset/css/search.css">
<link rel="icon" type="image/x-icon" href="../asset/images/icon/logo.ico">

<nav>
    <ul class="nav-left">
        <li>
            <a href="../pages/index.php">
                Trang Chủ
            </a>
        </li>
        |
        <li class="dropdown">
            <a class="dropdown-btn">
                Thú Cưng 
            </a>
            <div class="dropdown-content">
                <a href="../pages/index.php?page=cat">
                    <img src="../asset/images/icon/cat-ico.png" alt="Cat Icon" style="vertical-align: middle;" />
                    Mèo
                </a>
                <a href="../pages/index.php?page=dog">
                    <img src="../asset/images/icon/dog-ico.png" alt="Dog Icon" style="vertical-align: middle;" />
                    Chó
                </a>
                <a href="../pages/index.php?page=parrot">
                    <img src="../asset/images/icon/parrot-ico.png" alt="Parrot Icon" style="vertical-align: middle;" />
                    Vẹt
                </a>
            </div>
        </li>
        |
        <li>
            <a href="../pages/index.php?page=about">
                Giới Thiệu
            </a>
        </li>
    </ul>
    
    <ul class="nav-center">
        <li class="search-container">
            <form name="formtim" action="../pages/index.php" method="get" class="search-form">
                <input type="hidden" name="page" value="search">
                <input name="tukhoa" id="tukhoa" type="text" placeholder="Tìm kiếm" />
                <input name="btntim" id="btntim" type="image" src="../asset/images/icon/search.png" alt="Search Button">
            </form>
        </li>
    </ul>
    
    <ul class="nav-right">
    <li class="nav-cart dropdown">
        <a class="text-cart dropdown-btn" href="../pages/index.php?page=cart">
            <img src="../asset/images/icon/cart-ico.png" alt="Cart Icon" />
            Giỏ hàng
        </a>
        <?php if (!$logged_in): ?>
        <div class="dropdown-content">
            <a href="../pages/index.php?page=order_guest">
                <img src="../asset/images/icon/userprofile.png" style="vertical-align: middle;" />
                Lịch sử đặt hàng
            </a>
        </div>
        <?php endif; ?>
    </li>

 
    <?php if ($logged_in): 
        require '../config/config.php'; // Ensure database connection
         
        // Check if the user is logged in
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: login.php'); // Redirect to login page if not logged in
            exit();
        }
        
        // Get the username from session
        $username = $_SESSION['username'];
        
        // Retrieve user information from the database
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if (!$user) {
                echo "User does not exist.";
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
        
        // Handle user information update
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            $dob = $_POST['dob'];
            // Update the password if a new one is provided
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['pass'];
        
            try {
                $sql = "UPDATE users SET phone = :phone, email = :email, address = :address, dob = :dob, pass = :pass WHERE username = :username";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':dob', $dob);
                $stmt->bindParam(':pass', $password);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
        
                // Redirect to index.php after successful update
            } catch (PDOException $e) {
                echo "Error updating information: " . $e->getMessage();
            }
        }
        


    ?>

    <!-- Người dùng đã đăng nhập -->
    <li class="dropdown">
        <a class="dropdown-btn">
            <img src="../asset/images/icon/user.png" alt="User Icon" />
            <?php echo htmlspecialchars($user['username']); ?>
        </a>
        <div class="dropdown-content">
            <a href="../pages/index.php?page=index_user">
                <img src="../asset/images/icon/userprofile.png" style="vertical-align: middle;" />
                Thông tin
            </a>
            <a href="../pages/logout.php">
                <img src="../asset/images/icon/logout-ico.png" style="vertical-align: middle;" />
                Đăng xuất
            </a>
        </div>
    </li>
<?php else: ?>
    <!-- Người dùng chưa đăng nhập -->
    <li class="dropdown">
        <a class="dropdown-btn">
            <img src="../asset/images/icon/user.png" alt="User Icon" />
            Tài khoản
        </a>
        <div class="dropdown-content">
            <a href="" onclick="openLoginModal(); return false;">
                <img class="circle-button" src="../asset/images/icon/login-ico.png" alt="Login" style="vertical-align: middle;">
                Đăng nhập
            </a>
            <a href="" onclick="openRegisterModal(); return false;">
                <img class="circle-button" src="../asset/images/icon/register-ico.png" alt="Register" style="vertical-align: middle;">
                Đăng ký
            </a>
        </div>
    </li>
<?php endif; ?>
