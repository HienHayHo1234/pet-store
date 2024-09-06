<?php
// Kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$userdb = "root";
$passdb = "";
$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $userdb, $passdb);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Hàm lấy danh sách thú cưng từ bảng pets
function layDanhSachPets() {
    global $conn;
    $sql = "SELECT id, name, idLoai, price, priceSale, gender, quantity, urlImg, hot, description FROM pets";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm thêm thú cưng vào bảng pets
function themPets($id, $name, $price, $priceSale, $gender, $quantity, $urlImg, $idLoai, $description, $hot) {
    global $conn;
    $sql = "INSERT INTO pets (id, name, price, priceSale, gender, quantity, urlImg, idLoai, description, hot) 
            VALUES ('$id', '$name', $price, $priceSale, $gender, $quantity, '$urlImg', '$idLoai', '$description', $hot)";
    $kq = $conn->exec($sql);
    return $kq;
}

// Hàm xóa thú cưng khỏi bảng pets
function xoaPets($id) {
    global $conn;
    $sql = "DELETE FROM pets WHERE id = '$id'";
    $kq = $conn->exec($sql);
    return $kq;
}

// Hàm lấy chi tiết thú cưng từ bảng pets
function layChiTietPets($id) {
    global $conn;
    $sql = "SELECT id, name, price, priceSale, gender, quantity, urlImg, idLoai, description, hot 
            FROM pets WHERE id = '$id'";
    $kq = $conn->query($sql);
    if ($kq == null) {
        return false;
    } else {
        return $kq->fetch();
    }
}

// Hàm cập nhật thông tin thú cưng
function capnhatPets($id, $name, $price, $priceSale, $gender, $quantity, $urlImg, $idLoai, $description, $hot) {
    global $conn;
    $sql = "UPDATE pets SET name = '$name', price = $price, priceSale = $priceSale, gender = $gender, 
            quantity = $quantity, urlImg = '$urlImg', idLoai = '$idLoai', description = '$description', hot = $hot 
            WHERE id = '$id'";
    $kq = $conn->exec($sql);
    return $kq;
}
function layChiTietDonHang($id) {
    global $conn;
    $sql = "SELECT o.idOrder AS order_id, o.orderDate, o.status, 
                   SUM(od.quantity * od.price) AS total_price
            FROM orders o
            JOIN order_details od ON o.idOrder = od.order_id
            WHERE o.idOrder = :order_id
            GROUP BY o.idOrder, o.orderDate, o.status";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':order_id', $id);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $orders;
}

function layDanhSachDonHang() {
    global $conn;
    // Truy vấn để lấy danh sách đơn hàng
    $sql = "SELECT o.idOrder AS order_id, o.orderDate, o.status AS order_status, 
                   SUM(od.quantity * od.price) AS total_price
            FROM orders o
            JOIN order_details od ON o.idOrder = od.order_id
            GROUP BY o.idOrder, o.orderDate, o.status
            ORDER BY o.orderDate DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $orders;
}

// Hàm cập nhật đơn hàng
function capnhatDonHang($id, $status) {
    global $conn;
    $sql = "UPDATE orders SET status = :status WHERE idOrder = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':order_id', $id);
    return $stmt->execute();
}

// Hàm xóa đơn hàng
function xoaDonHang($id) {
    global $conn;
    try {
        $conn->beginTransaction();

        // Xóa chi tiết đơn hàng
        $delete_details_sql = "DELETE FROM order_details WHERE order_id = :order_id";
        $delete_details_stmt = $conn->prepare($delete_details_sql);
        $delete_details_stmt->bindParam(':order_id', $id);
        $delete_details_stmt->execute();

        // Xóa đơn hàng
        $delete_order_sql = "DELETE FROM orders WHERE idOrder = :order_id";
        $delete_order_stmt = $conn->prepare($delete_order_sql);
        $delete_order_stmt->bindParam(':order_id', $id);
        $delete_order_stmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}
