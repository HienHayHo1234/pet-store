<?php
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
    <link rel="stylesheet" href="../asset/css/profile.css"> <!-- Custom CSS for profile page -->
    <body>
    <div class="tt">
        
        <div class="content">
            
            <h2>Thông Tin Tài Khoản</h2>
            <form class="profile" action="" method="post">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>

                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

                <label for="dob">Ngày sinh:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">

                <button type="submit">Cập nhật thông tin</button>
            </form>
        </div>
    </div>
</body>