let isLoggedIn = false;
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn form gửi dữ liệu theo cách mặc định

    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../config/check-login.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    $checkLogin = false;
    // Chuyển đổi FormData thành chuỗi URL-encoded
    const urlEncodedData = new URLSearchParams(formData).toString();
    xhr.send(urlEncodedData);

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    isLoggedIn = true; 
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
// Hàm kiểm tra trạng thái đăng nhập
function checkLoginStatus() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/check-login-status.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                isLoggedIn = response.logged_in;
                if (isLoggedIn) {
                    // Người dùng đã đăng nhập, thực hiện các hành động cần thiết
                    console.log('Người dùng đã đăng nhập');
                } else {
                    console.log('Người dùng chưa đăng nhập');
                }
            } catch (error) {
                console.error('Lỗi khi xử lý dữ liệu từ server:', error);
            }
        } else {
            console.error('Lỗi khi gửi yêu cầu. Status code:', xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Có lỗi xảy ra khi gửi yêu cầu.');
    };

    xhr.send();
}

// Gọi hàm kiểm tra trạng thái đăng nhập khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
    checkLoginStatus();
});


// hieu ung
