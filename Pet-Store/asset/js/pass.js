document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const closeForgotPasswordModalButton = document.getElementById('closeForgotPasswordModalButton');

    // Mở modal quên mật khẩu
    function openForgotPasswordModal() {
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        if (forgotPasswordModal) {
            forgotPasswordModal.style.display = 'block';
        } else {
            console.error('Modal quên mật khẩu không tồn tại.');
        }
    }

    // Đóng modal quên mật khẩu
    function closeForgotPasswordModal() {
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        if (forgotPasswordModal) {
            forgotPasswordModal.style.display = 'none';
        } else {
            console.error('Modal quên mật khẩu không tồn tại.');
        }
    }

    // Hiển thị thông báo thành công hoặc lỗi
    function displayMessage(message, type) {
        const messageBox = document.createElement('div');
        messageBox.textContent = message;
        messageBox.className = type === 'success' ? 'success-message' : 'error-message';
        document.body.appendChild(messageBox);

        // Tự động ẩn sau vài giây
        setTimeout(() => {
            messageBox.remove();
        }, 3000);
    }

    // Hiển thị thông báo thành công hoặc lỗi
    function displayMessage(message, type) {
        const messageBox = document.createElement('div');
        messageBox.textContent = message;
        messageBox.className = type === 'success' ? 'success-message' : 'error-message';
        document.body.appendChild(messageBox);

        // Tự động ẩn sau vài giây
        setTimeout(() => {
            messageBox.remove();
        }, 3000);
    }

    // Mở modal quên mật khẩu
    function openForgotPasswordModal() {
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        if (forgotPasswordModal) {
            forgotPasswordModal.style.display = 'block';
        } else {
            console.error('Modal quên mật khẩu không tồn tại.');
        }
    }

    // Đóng modal quên mật khẩu
    function closeForgotPasswordModal() {
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        if (forgotPasswordModal) {
            forgotPasswordModal.style.display = 'none';
        } else {
            console.error('Modal quên mật khẩu không tồn tại.');
        }
    }

    // Hiển thị thông báo thành công hoặc lỗi
    function displayMessage(message, type) {
        const messageBox = document.createElement('div');
        messageBox.textContent = message;
        messageBox.className = type === 'success' ? 'success-message' : 'error-message';
        document.body.appendChild(messageBox);

        // Tự động ẩn sau vài giây
        setTimeout(() => {
            messageBox.remove();
        }, 3000);
    }

    // Xử lý form quên mật khẩu
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

            const formData = new FormData(forgotPasswordForm);
            const formData = new FormData(forgotPasswordForm);

            fetch('quenpass.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    closeForgotPasswordModal(); // Đóng modal quên mật khẩu
                    openLoginModal(); // Mở modal đăng nhập
                    displayMessage('Gửi mail thành công!', 'success'); // Hiển thị thông báo thành công
                    displayMessage('Gửi mail thành công!', 'success'); // Hiển thị thông báo thành công
                } else {
                    displayMessage('Có lỗi xảy ra, vui lòng thử lại.', 'error'); // Hiển thị thông báo lỗi
                }
            })
            .catch(() => {
                displayMessage('Có lỗi xảy ra khi gửi yêu cầu.', 'error'); // Hiển thị thông báo lỗi
            });
        });
    } else {
        console.error('Form quên mật khẩu không tồn tại.');
            fetch('quenpass.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    closeForgotPasswordModal(); // Đóng modal quên mật khẩu
                    openLoginModal(); // Mở modal đăng nhập
                    displayMessage('Gửi mail thành công!', 'success'); // Hiển thị thông báo thành công
                } else {
                    displayMessage('Có lỗi xảy ra, vui lòng thử lại.', 'error'); // Hiển thị thông báo lỗi
                    displayMessage('Có lỗi xảy ra, vui lòng thử lại.', 'error'); // Hiển thị thông báo lỗi
                }
            })
            .catch(() => {
                displayMessage('Có lỗi xảy ra khi gửi yêu cầu.', 'error'); // Hiển thị thông báo lỗi
                displayMessage('Có lỗi xảy ra khi gửi yêu cầu.', 'error'); // Hiển thị thông báo lỗi
            });
        });
    } else {
        console.error('Form quên mật khẩu không tồn tại.');
    }

    // Gắn sự kiện lắng nghe cho nút đóng modal quên mật khẩu
    if (closeForgotPasswordModalButton) {
        closeForgotPasswordModalButton.addEventListener('click', closeForgotPasswordModal);
    }

    // Đóng modal khi nhấn ra ngoài vùng modal
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };

    // Mở modal đăng nhập (nếu có)
    function openLoginModal() {
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.style.display = 'block';
        } else {
            console.error('Modal đăng nhập không tồn tại.');
        }
    }

    // Mở modal đăng nhập (nếu có)
    function openLoginModal() {
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.style.display = 'block';
        } else {
            console.error('Modal đăng nhập không tồn tại.');
        }
    }
});
