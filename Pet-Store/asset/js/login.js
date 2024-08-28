document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');
    const closeModalButton = document.getElementById('closeModalButton');

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
                // Đăng nhập thành công
                closeLoginModal(); // Đóng modal
                location.reload(); // Tải lại trang để cập nhật trạng thái đăng nhập
            } else {
                // Hiển thị lỗi trong modal nếu có
                errorMessage.innerHTML = data.error;
            }
        })
        .catch(error => {
            errorMessage.innerHTML = "Đã xảy ra lỗi khi gửi yêu cầu.";
        });
    });

    window.openLoginModal = () => document.getElementById('loginModal').style.display = 'block';
    window.closeLoginModal = () => document.getElementById('loginModal').style.display = 'none';

    // Gắn sự kiện lắng nghe cho nút "X" để đóng modal
    closeModalButton.addEventListener('click', closeLoginModal);
});
