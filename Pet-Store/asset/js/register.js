document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const errorMessage = document.getElementById('error-message');

    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeRegisterModal();
                    openLoginModal();
                } else {
                    errorMessage.textContent = data.error;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = "Đã xảy ra lỗi khi gửi yêu cầu.";
            });
        });
    }

    // Các hàm mở/đóng modal và xử lý nút quay lại
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

    const closeRegisterModalButton = document.getElementById('closeRegisterModalButton');
    if (closeRegisterModalButton) {
        closeRegisterModalButton.addEventListener('click', closeRegisterModal);
    }

    const backToLogin = document.getElementById('backToLogin');
    if (backToLogin) {
        backToLogin.onclick = function() {
            closeRegisterModal();
            openLoginModal();
        };
    }
});
