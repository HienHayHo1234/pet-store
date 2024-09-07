<?php
require 'functions.php'; // Ensure database connection

// Check if the user is logged in
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header('Location: index.php'); // Redirect to login page if not logged in
//     exit();
// }

// Get the order ID from the URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
if (!$order_id) {
    echo "Order ID is required.";
    exit();
}

// Retrieve order information from the database
try {
    $sql = "SELECT * FROM orders WHERE idOrder = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch();

    if (!$order) {
        echo "Order does not exist.";
        exit();
    }

    // Retrieve order details
    $sql = "SELECT * FROM order_details WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order_details = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Handle order update
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalAmount = $_POST['totalAmount'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE orders SET totalAmount = :totalAmount, status = :status WHERE idOrder = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':totalAmount', $totalAmount);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        $message = "Order information has been updated successfully.";
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "index.php?page=order_list";
                }, 2000);
              </script>';
    } catch (PDOException $e) {
        $message = "Error updating order: " . $e->getMessage();
    }
}
?>
<link rel="stylesheet" href="../asset/css/profile.css">
<body>
    <div class="tt">
        <div class="content">
            <h2>Order Information</h2>
            <form id="orderForm" class="order" action="" method="post">
                <label for="order_id">Order ID:</label>
                <input type="text" id="order_id" name="order_id" value="<?php echo htmlspecialchars($order['idOrder']); ?>" disabled>
                <label for="totalAmount">Total Amount:</label>
                <input type="text" id="totalAmount" name="totalAmount" value="<?php echo htmlspecialchars($order['totalAmount']); ?>" required>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Chờ xử lý" <?php echo $order['status'] == 'Chờ xử lý' ? 'selected' : ''; ?>>Chờ xử lý</option>
                    <option value="Đang xử lý" <?php echo $order['status'] == 'Đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="Hoàn thành" <?php echo $order['status'] == 'Hoàn thành' ? 'selected' : ''; ?>>Hoàn thành</option>
                    <option value="Đã hủy" <?php echo $order['status'] == 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                    <option value="Đang vận chuyển" <?php echo $order['status'] == 'Đang vận chuyển' ? 'selected' : ''; ?>>Đang vận chuyển</option>
                    <option value="Đã giao" <?php echo $order['status'] == 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                    <option value="Đã hoàn tiền" <?php echo $order['status'] == 'Đã hoàn tiền' ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                </select>

                <button type="submit">Update Order</button>
                <?php if ($message) { echo '<p style="color:red;">' . htmlspecialchars($message) . '</p>'; } ?>
            </form>

            <h3>Order Details</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>Pet ID</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['pet_id']); ?></td>
                            <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($detail['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
