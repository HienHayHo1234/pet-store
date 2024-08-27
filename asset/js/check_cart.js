function checkCartItems() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../config/check_cart.php', true); // Đảm bảo đường dẫn đúng
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.itemCount > 0) {
                displayCartIcon();
            }
        }
    };
    xhr.send();
}

function displayCartIcon() {
    let cartIcon = document.querySelector('.new-cart');
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
