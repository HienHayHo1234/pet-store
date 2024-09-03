<link rel="stylesheet" href="../asset/css/register.css">
<!-- Modal Form Đăng Ký -->
<div id="registerModal" class="login-modal" style="display:none">
    <div class="login-modal-content">
        <span id="closeRegisterModalButton" class="close">&times;</span>
        <!-- Mũi tên quay lại form đăng nhập -->
        <span id="backToLogin" class="back-arrow">&#8592; Quay lại</span> 
        <h2>Đăng Ký</h2>
        <form action="register.php" method="post" class="login-form">
            <label for="register-username">Tên đăng nhập</label>
            <input type="text" id="register-username" name="username" required><br>
            <label for="register-email">Email</label><br>
            <input type="email" id="register-email" name="email" required><br>
            <label for="register-password">Mật khẩu</label><br>
            <input type="password" id="register-password" name="password" required><br>
            <label for="register-confirmPassword">Xác nhận mật khẩu</label><br>
            <input type="password" id="register-confirmPassword" name="confirmPassword" required><br>
            <div class="login-button-container">
                <button type="submit" name="btn1">Gửi</button>
                <button type="reset">Xóa</button>
            </div>
        </form>
    </div>
</div>
<script src="../asset/js/register.js"></script>
