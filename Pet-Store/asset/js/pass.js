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

    // Xử lý form quên mật khẩu
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

            const formData = new FormData(forgotPasswordForm);

            fetch('quenpass.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    closeForgotPasswordModal(); // Đóng modal quên mật khẩu
                    openLoginModal(); // Mở modal đăng nhập
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(() => {
                alert('Có lỗi xảy ra khi gửi yêu cầu.');
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
});
