document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');
    const closeLoginModalButton = document.getElementById('closeLoginModalButton');
    const closeRegisterModalButton = document.getElementById('closeRegisterModalButton');
    const backToLogin = document.getElementById('backToLogin');

    // Xử lý form đăng nhập
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

        const formData = new FormData(loginForm);

        fetch('../pages/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeLoginModal(); // Đóng modal đăng nhập
                location.reload(); // Tải lại trang để cập nhật trạng thái đăng nhập
            } else {
                errorMessage.innerHTML = data.error;
            }
        })
        .catch(error => {
            errorMessage.innerHTML = "Đã xảy ra lỗi khi gửi yêu cầu.";
        });
    });

    window.openLoginModal = function() {
        document.getElementById('loginModal').style.display = 'block';
    };

    window.closeLoginModal = function() {
        document.getElementById('loginModal').style.display = 'none';
    };

    window.openRegisterModal = function() {
        document.getElementById('registerModal').style.display = 'block';
    };

    window.closeRegisterModal = function() {
        document.getElementById('registerModal').style.display = 'none';
    };

    // Gắn sự kiện lắng nghe cho nút đóng modal đăng nhập
    if (closeLoginModalButton) {
        closeLoginModalButton.addEventListener('click', closeLoginModal);
    }

    // Gắn sự kiện lắng nghe cho nút đóng modal đăng ký
    if (closeRegisterModalButton) {
        closeRegisterModalButton.addEventListener('click', function() {
            closeRegisterModal();
            closeLoginModal(); // Đóng cả hai modal khi nhấn nút X trong modal đăng ký
        });
    }

    // Xử lý nút quay lại từ modal đăng ký
    if (backToLogin) {
        backToLogin.onclick = function() {
            closeRegisterModal();
            openLoginModal();
        };
    }
});
