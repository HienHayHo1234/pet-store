<link rel="stylesheet" href="../asset/css/banner.css">
<link rel="stylesheet" href="../asset/css/pets.css">
<section>
    <div class="slider-container">
            <div class="slider" id="slider">
                <div class="slide"><img src="../asset/images/banner/2.jpg" alt="Image 1"></div>
                <div class="slide"><img src="../asset/images/banner/3.jpg" alt="Image 2"></div>
                <div class="slide"><img src="../asset/images/banner/4.jpg" alt="Image 3"></div>
                <div class="slide"><img src="../asset/images/banner/5.jpg" alt="Image 4"></div>
                <div class="slide"><img src="../asset/images/banner/6.jpg" alt="Image 5"></div>
            </div>
            <button class="arrow left-arrow" id="prevBtn">&#10094;</button>
            <button class="arrow right-arrow" id="nextBtn">&#10095;</button>
            <div class="indicators" id="indicators"></div>
    </div>
</section>
<h2>Loại Thú Cưng</h2>
<section class="pet">
    <div>
        <h2>Chó</h2>
        <a href="index.php?page=dog"><img src="../asset/images/dog/head.png" alt="Chó"></a>
        <h3>Chó là loài vật nuôi trung thành và đáng yêu.</h3>
    </div>
    <div>
        <h2>Mèo</h2>
        <a href="index.php?page=cat"><img src="../asset/images/cat/head.png" alt="Mèo"></a>
        <h3>Mèo là loài vật nuôi thanh lịch và dễ thương.</h3>
    </div>
    <div>
        <h2>Vẹt</h2>
        <a href="index.php?page=parrot"><img src="../asset/images/parrot/head.png" alt="Vẹt"></a>
        <h3>Chim là loài vật nuôi đáng yêu và đáng eu</h3>
    </div>
</section>

<section id="product">
    <h2>Thú Cưng Cháy Hàng</h2>
    <?php require_once("hot.php"); ?>
    <h2>Thú Cưng</h2>
    <?php require_once("pets.php"); ?>
</section>
<script src="../asset/js/banner.js"></script>