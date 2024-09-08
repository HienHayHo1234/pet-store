<?php
require 'functions.php'; // Ensure database connection

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

        $message = "Cập nhật đơn hàng thành công";
        echo '<script type="text/javascript">
            window.location.href = "index.php?page=orders";
      </script>';
    } catch (PDOException $e) {
        $message = "Error updating order: " . $e->getMessage();
    }
}   

?>


<link rel="stylesheet" href="../asset/css/profile.css">
<body>
    <div class="container">
        <div class="tt">
            <div class="content">
                <h2>Thông tin đơn hàng</h2>
                <form id="orderForm" class="order" action="" method="post">
                    <div class="form-group">
                        <label for="order_id">Mã đơn hàng:</label>
                        <input type="text" id="order_id" name="order_id" value="<?php echo htmlspecialchars($order['idOrder']); ?>" disabled class="input-disabled">
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái:</label>
                        <select id="status" name="status" required class="input-select">
                            <option value="Chờ xử lý" <?php echo $order['status'] == 'Chờ xử lý' ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="Đang xử lý" <?php echo $order['status'] == 'Đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                            <option value="Hoàn thành" <?php echo $order['status'] == 'Hoàn thành' ? 'selected' : ''; ?>>Hoàn thành</option>
                            <option value="Đã hủy" <?php echo $order['status'] == 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                            <option value="Đang vận chuyển" <?php echo $order['status'] == 'Đang vận chuyển' ? 'selected' : ''; ?>>Đang vận chuyển</option>
                            <option value="Đã giao" <?php echo $order['status'] == 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                            <option value="Đã hoàn tiền" <?php echo $order['status'] == 'Đã hoàn tiền' ? 'selected' : ''; ?>>Đã hoàn tiền</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật đơn hàng</button>
                    <!-- Nút quay lại -->
                    <a href="index.php?page=orders" class="btn btn-back">Trở về</a>
                    <?php if ($message) { echo '<p class="error-message">' . htmlspecialchars($message) . '</p>'; } ?>
                </form>

                <h3>Chi tiết đơn hàng</h3>
                <table class="order-details">
                    <thead>
                        <tr>
                            <th>Mã thú cưng</th>
                            <th>Giá</th>
                            <th>Số lượng</th>                       
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $totalQuantity = 0; // Biến để tính tổng số lượng
                    $totalAmount = 0; // Biến để tính tổng tiền

                    foreach ($order_details as $detail): 
                        $itemTotal = $detail['price'] * $detail['quantity'];
                        $totalQuantity += $detail['quantity'];
                        $totalAmount += $itemTotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['pet_id']); ?></td>
                            <td><?php echo number_format($detail['price'], 0, '.', '.'); ?> VND</td>

                            <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                            <td><?php echo number_format($itemTotal, 0, '.', '.'); ?> VND</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Tổng cộng:</strong></td>
                            <td><strong><?php echo number_format($totalQuantity, 0, '.', '.'); ?></strong></td>
                            <td><strong><?php echo number_format($totalAmount, 0, '.', '.'); ?> VND</strong></td>
                        </tr>
                    </tfoot>
                </table>
                
            </div>
        </div>
    </div>
</body>



<style>
    .btn-secondary {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: white;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    margin-top: 20px;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

    .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.input-disabled, .input-text, .input-select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
}

.error-message {
    color: red;
    font-size: 14px;
    text-align: center;
}

table.order-details {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table.order-details th, table.order-details td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table.order-details th {
    background-color: #007bff;
}

.btn {
    padding: 12px 24px;
    text-decoration: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: inline-block;
    margin: 0 5px;
    background-color: #007bff;
}

.btn-back {
    background-color: #6c757d;
}

.btn:hover {
    background-color: #0056b3;
}

</style>