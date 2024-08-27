<?php
require '../config/config.php';


// Giả sử user_id là 1, bạn có thể thay đổi theo hệ thống của bạn
$user_id = 1;

// Kiểm tra kết nối có tồn tại hay không
if (!$conn) {
    die("Kết nối cơ sở dữ liệu thất bại.");
}

try {
    // Truy vấn các mặt hàng trong giỏ hàng của người dùng
    $stmt = $conn->prepare("
        SELECT pets.id, pets.name, pets.price, pets.priceSale, pets.urlImg, cart_items.quantity 
        FROM cart_items 
        JOIN pets ON cart_items.pet_id = pets.id 
        WHERE cart_items.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cartItems === false) {
        throw new Exception("Không thể truy xuất giỏ hàng.");
    }

    // Cập nhật phiên với thông tin giỏ hàng
    $_SESSION['cart-items'] = $cartItems;

    // Tính tổng số tiền
    $totalAmount = 0;
    if (!empty($cartItems)) {
        foreach ($cartItems as $item) {
            $totalAmount += $item['priceSale'] * $item['quantity'];
        }
    }
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
    exit;
}

?>

<link rel="stylesheet" href="../asset/css/cart.css?v=<?php echo time(); ?>">

<div class="cart-flex">
    <!-- -----------bảng invoice -------------->
    <div class="invoice-flex">
        <?php if (!empty($cartItems)): ?>
            <div class="container-invoice-list">
                <?php foreach ($cartItems as $item): ?>
                    <div class="invoice-item" data-id="<?php echo htmlspecialchars($item['id']); ?>">
                        <input type="checkbox" class="checkbox-btn-cart" data-id="<?php echo htmlspecialchars($item['id']); ?>"
                            onclick="selectItem('<?php echo htmlspecialchars($item['id']); ?>')">

                        <div class="image-container">
                            <img class="imgInvoice" src="<?php echo htmlspecialchars($item['urlImg']); ?>"
                                alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>

                        <div class="invoice-text">
                            <p class="name-pet-cart"><?php echo htmlspecialchars($item['name']); ?></p>
                            <p>Giá: <?php echo number_format($item['priceSale'], 0, ',', '.'); ?>đ</p>
                            <p class="count">
                                Số lượng:
                                <button class="quantity-btn minus" data-id="<?php echo $item['id']; ?>">-</button>
                                <span id="quantity-"><?php echo htmlspecialchars($item['quantity']); ?></span>
                                <button class="quantity-btn plus" data-id="<?php echo $item['id']; ?>">+</button>
                            </p>
                            <p class="totalPrice" data-id="<?php echo $item['id']; ?>">Tổng giá:
                                <?php echo number_format($item['priceSale'] * $item['quantity'], 0, ',', '.'); ?>đ</p>
                        </div>

                        <button class="btn-cancel-pet"
                            onclick="removeFromCart('<?php echo htmlspecialchars($item['id']); ?>')">Hủy</button>
                        <button class="btn-order-pet" onclick="showOrderForm()">Đặt hàng</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- nút đặt tất cả -->
            <div class="order-summary">
                <p>Tổng số tiền tất cả sản phẩm:
                    <span class="total-amount">
                        <?php echo number_format($totalAmount, 0, ',', '.'); ?>đ
                    </span>
                </p>
                <button class="btn-order-all" onclick="placeOrder()">Đặt hàng tất cả</button>
            </div>
        <?php else: ?>
            <p style="font-size: larger">Không có sản phẩm nào trong giỏ hàng!</p>
        <?php endif; ?>
    </div>

    <!-- -------------------truck-kun----------  -->
    <div id="truck" class="truck-flex" style="display: none">
        <img src="../asset/images/icon/truck-cart.png" alt="" class="truck-kun">
    </div>
</div>

<!-- ---------------------------form đặt hàng---------------------- -->
<div class="cart-form" id="orderForm" style="display: none;">
    <!-- hình người giao -->
    <div>
        <p id="infoMessage" class="text-chat">HÃY ĐIỀN THÔNG TIN GIAO HÀNG</p>
        <p id="formCompleteMessage" class="text-chat" style="display: none;">
            HÃY ĐƯA THÔNG TIN CHO TÔI
        </p>
        <img class="img-note" src="../asset/images/background/background-cart.png" alt="">
    </div>

    <!-- nút xóa -->
    <img onclick="btnClose()" class="btn-close" src="../asset/images/icon/close.png" alt="">

    <form id="orderFormElement" action="" method="post" class="order-form">
        <h2>Đặt hàng sản phẩm</h2>

        <label for="name">Tên của bạn:</label>
        <input type="text" id="name" name="name" required>

        <label for="address">Địa chỉ giao hàng:</label>
        <input type="text" id="address" name="address" required>

        <label for="phone">Số điện thoại:</label>
        <input type="tel" id="phone" name="phone" required>


        <!-- Hiển thị tổng số tiền -->
        <label for="totalAmount" class="total-amount">
            Tổng số tiền: <span id="totalAmount">0đ</span>
        </label>

        <!-- nút gửi -->
        <button type="submit" class="btn-submit" style="display: none">
            <img src="../asset/images/icon/take-form.png" alt="Gửi">
        </button>
    </form>


</div>

<script src="../asset/js/form-cart.js"></script>
<script src="../asset/js/cart.js"></script>