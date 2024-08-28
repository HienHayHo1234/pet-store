<?php
// Kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "pet-store";
$username = "root";
$password = "";

if (isset($_GET['action']) && $_GET['action'] === 'getPetDetails' && isset($_GET['id'])) {
    $petId = $_GET['id'];

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM pets WHERE id = :id");
        $stmt->bindParam(':id', $petId);
        $stmt->execute();

        $pet = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pet) {
            echo json_encode($pet);
        } else {
            echo json_encode(['error' => 'Pet not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pet['name']); ?></title>
    <link href="../asset/css/DetailPet.css" rel="stylesheet">
</head>
<body>
<div id="modal" class="custom-modal">
    <div class="custom-modal-content">
        <div>
            <img class="custom-modal-img" src="<?php echo htmlspecialchars($pet['image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($pet['name'] ?? ''); ?>">
        </div>
        <div>
            <h1 class="custom-modal-title"><?php echo htmlspecialchars($pet['name'] ?? ''); ?></h1>

            <p class="custom-modal-price">Giá: <?php echo number_format($pet['custom-modal-price'], 0, ',', '.'); ?>đ</p>
            <p class="custom-modal-sale-price">Giá khuyến mãi: <?php echo number_format($pet['custom-modal-sale-price'], 0, ',', '.'); ?>đ</p>
            <p class="custom-modal-quantity">Số lượng còn lại: <?php echo htmlspecialchars($pet['quantity'] ?? ''); ?></p>
            <p class="custom-modal-description">Mô tả: <?php echo htmlspecialchars($pet['description'] ?? ''); ?></p>
            <button class="add-to-cart" onclick="addToPet('<?php echo htmlspecialchars($pet['id'] ?? ''); ?>')">Giỏ hàng</button>
        </div>
        <span class="custom-close">&times;</span>   
    </div>
</div>
</body>
</html>
