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
</div>
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title"></h2>
        <img class="modal-img" src="" alt="">
        <p class="modal-price">Giá: </p>
        <p class="modal-sale-price">Giá khuyến mãi: </p>
        <p class="modal-quantity">Số lượng còn lại: </p>
        <p class="modal-description">Mô tả: </p>
    </div>
</div>

</body>
</html>

