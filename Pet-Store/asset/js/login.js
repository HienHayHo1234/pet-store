document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../config/check-login.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Chuyển đổi FormData thành chuỗi URL-encoded
    const urlEncodedData = new URLSearchParams(formData).toString();
    xhr.send(urlEncodedData);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Đăng nhập thành công, đóng modal và tải lại trang
                    closeLoginModal();
                    location.reload();
                    
                } else {
                    // Hiển thị lỗi nếu có
                    console.log("Mật khẩu từ cơ sở dữ liệu:", response.password);
                    document.getElementById('error-message').innerHTML = response.error;
                }
            } catch (error) {
                document.getElementById('error-message').innerHTML = 'Lỗi khi xử lý dữ liệu từ server.';
            }
        } else {
            document.getElementById('error-message').innerHTML = 'Lỗi khi gửi yêu cầu. Status code: ' + xhr.status;
        }
    };

    xhr.onerror = function() {
        document.getElementById('error-message').innerHTML = 'Có lỗi xảy ra khi gửi yêu cầu.';
    };

    // Ghi mật khẩu vào console (không nên thực hiện điều này trong môi trường thực tế)
    console.log("Mật khẩu nhập vào:", formData.get('password'));
});


function openLoginModal(){
    const loginDiv = document.getElementById('loginModal');
    loginDiv.style.display = 'block';
}

function closeLoginModal(){
    const loginDiv = document.getElementById('loginModal');
    loginDiv.style.display = 'none';
}

function openForgotPasswordModal() {
    const forgotPasswordDiv = document.getElementById('forgotPasswordModal');
    forgotPasswordDiv.style.display = 'block';
}
