function checkCartItems() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/check_cart.php', true); // Đảm bảo đường dẫn đúng
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.itemCount > 0) {
                    displayCartIcon();
                }
            } catch (error) {
                console.error('Response không phải là JSON hợp lệ:', xhr.responseText);
                // Xử lý lỗi ở đây, ví dụ hiển thị thông báo lỗi cho người dùng
            }
        } else {
            console.error('Lỗi HTTP:', xhr.status, xhr.statusText);
            // Xử lý lỗi HTTP ở đây
        }
    };
    xhr.send();
}

function displayCartIcon() {
    let cartIcon = document.querySelector('.new-icon-cart');
    if (!cartIcon) {
        cartIcon = document.createElement('img');
        cartIcon.className = 'new-icon-cart';
        cartIcon.src = '../asset/images/icon/new-cart.png';
        document.querySelector('.nav-cart').appendChild(cartIcon);
    }
}


document.addEventListener("DOMContentLoaded", function() {
    checkCartItems();
});