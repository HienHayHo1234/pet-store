<link rel="stylesheet" href="../asset/css/index.css">
<link rel="stylesheet" href="../asset/css/banner.css">
<link rel="stylesheet" href="../asset/css/search.css">
<link rel="icon" type="image/x-icon" href="../asset/images/icon/logo.ico">

<nav>
    <ul class="nav-left">
        <li>
            <a href="../pages/index.php">
                <!-- <img src="../asset/images/icon/home-ico.png" alt="Home Icon" /> -->
                Trang Chủ
            </a>
        </li>|

        <li class="dropdown">
            <a class="dropdown-btn">
                <!-- <img src="../asset/images/icon/pet-ico.png" alt="Pet Icon" /> -->
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
                <!-- <img src="../asset/images/icon/about-ico.png" alt="About Icon" /> -->
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
        <li class="nav-cart">
            <a class="text-cart" href="../pages/index.php?page=cart">
                <img src="../asset/images/icon/cart-ico.png" alt="Cart Icon" />
                Giỏ hàng
            </a>
        </li>
        |
        <li class="dropdown">
            <div class="dropdown-btn">
            <a href="">
                <img src="../asset/images/icon/user.png" alt="User Icon" />
                Tài khoản
            </a>
            </div>
            <div class="dropdown-content">
                <a href="" onclick="openLoginModal(); return false;">
                            <img class="circle-button" src="../asset/images/icon/user.png" alt="Login">
                            
                            Đăng nhập
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
    </ul>

</nav>