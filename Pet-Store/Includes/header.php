<link rel="stylesheet" href="../asset/css/index.css">
<link rel="stylesheet" href="../asset/css/banner.css">
<link rel="stylesheet" href="../asset/css/search.css">
<link rel="icon" type="image/x-icon" href="../asset/images/icon/logo.ico">

<nav>
    <ul class="nav-left">
        <li>
            <a href="../pages/index.php">
                <img src="../asset/images/icon/home-ico.png" alt="Home Icon" />
                Trang Chủ
            </a>
        </li>|

        <li class="dropdown">
            <a class="dropdown-btn">
                <img src="../asset/images/icon/pet-ico.png" alt="Pet Icon" />
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
                <img src="../asset/images/icon/about-ico.png" alt="About Icon" />
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
        <li>
            <a href="../pages/login.php" onclick="openLoginModal()">
                <img src="../asset/images/icon/user.png" alt="User Icon" />
                Tài khoản
            </a>
        </li>
    </ul>
</nav>
<!-- Modal Form Đăng Nhập -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span id="closeLoginModalButton" class="close">&times;</span>
        <h2>Đăng Nhập</h2>
        <form id="loginForm">
            <label for="login-username">Tên đăng nhập:</label><br>
            <input type="text" id="login-username" name="username" required><br><br>
            <label for="login-password">Mật khẩu:</label><br>
            <input type="password" id="login-password" name="password" required><br><br>
            <div>
                <label><input name="status" type="checkbox"> Ghi nhớ đăng nhập</label>
            </div>
            <hr>
            <div class="button-container">
                <input type="submit" value="Đăng Nhập">
                <button type="reset">Xóa</button>
            </div>
            <div id="error-message" class="error"></div>
            <p>Chưa có tài khoản? <a href="#" onclick="openRegisterModal(); return false;">Đăng ký</a></p>
            <p><a href="#" onclick="openForgotPasswordModal(); return false;">Quên mật khẩu?</a></p>
        </form>
    </div>
</div>

<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span id="closeForgotPasswordModalButton" class="close">&times;</span>
        <h2>Quên Mật Khẩu</h2>
        <form id="forgotPasswordForm" action="quenpass.php" method="post" class="col-5 m-auto bg-secondary p-2 text-white">
            <div class="form-group">
                <h4 class="border-bottom pb-2">QUÊN MẬT KHẨU</h4>
                <label for="email">Nhập email</label>
                <input id="email" class="form-control" name="email" type="email" required>
            </div>
            <div class="form-group">
                <button type="submit" name="btn1" class="btn btn-primary">Gửi yêu cầu</button>
            </div>
        </form>
    </div>
</div>




<!-- Modal Form Đăng Ký -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span id="closeRegisterModalButton" class="close">&times;</span>
        <!-- Mũi tên quay lại form đăng nhập -->
        <span id="backToLogin" class="back-arrow">&#8592; Quay lại</span> 
        <h2>Đăng Ký</h2>
        <form action="register.php" method="post">
            <label for="register-username">Tên đăng nhập</label>
            <input type="text" id="register-username" name="username" required><br>
            <label for="register-email">Email</label><br>
            <input type="email" id="register-email" name="email" required><br>
            <label for="register-password">Mật khẩu</label><br>
            <input type="password" id="register-password" name="password" required><br>
            <label for="register-confirmPassword">Xác nhận mật khẩu</label><br>
            <input type="password" id="register-confirmPassword" name="confirmPassword" required><br>
            <div class="button-container">
                <button type="submit" name = "btn1">Gửi</button>
                <button type="reset">Xóa</button>
            </div>
        </form>
    </div>
</div>
