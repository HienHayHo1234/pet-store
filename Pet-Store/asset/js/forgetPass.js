document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');

    forgotPasswordForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

        const formData = new FormData(forgotPasswordForm);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../config/forget_pass.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        // Chuyển đổi FormData thành chuỗi URL-encoded
        const urlEncodedData = new URLSearchParams(formData).toString();

        xhr.send(urlEncodedData);

        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        closeForgotPasswordModal(); // Đóng modal quên mật khẩu
                        openLoginModal(); // Mở modal đăng nhập
                    } else {
                        displayError(response.message); // Hiển thị thông báo lỗi từ server
                    }
                } catch (error) {
                    displayError('Lỗi khi xử lý dữ liệu từ server.');
                }
            } else {
                displayError('Lỗi khi gửi yêu cầu. Status code: ' + xhr.status);
            }
        };

        xhr.onerror = function() {
            displayError('Có lỗi xảy ra khi gửi yêu cầu.');
        };
    });

    function closeForgotPasswordModal() {
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        forgotPasswordModal.style.display = 'none';
    }

    function openLoginModal() {
        const loginModal = document.getElementById('loginModal');
        loginModal.style.display = 'block';
    }

    function displayError(message) {
        alert(message); // Có thể thay thế bằng cách hiển thị thông báo lỗi trên trang web
    }

    document.getElementById('closeForgotPasswordModalButton').addEventListener('click', function() {
        closeForgotPasswordModal();
    });
});
