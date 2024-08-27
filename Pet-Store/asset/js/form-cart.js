function showOrderForm(productId) {
    // Hiển thị form đặt hàng
    var orderForm = document.getElementById('orderForm');
    orderForm.style.display = '';
    // Gán giá trị productId vào hidden input
    document.getElementById('productId').value = productId;
    // Cuộn trang đến form đặt hàng
    orderForm.scrollIntoView({
        behavior: 'smooth'
    });
}

function btnClose() {
    var orderForm = document.getElementById('orderForm');
    orderForm.style.display = 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('orderFormElement');
    const infoMessage = document.getElementById('infoMessage');
    const formCompleteMessage = document.getElementById('formCompleteMessage');
    const submitButton = document.querySelector('.btn-submit');

    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const product = document.getElementById('product').value;

        if (name && address && phone  && product) {
            infoMessage.style.display = 'none';
            formCompleteMessage.style.display = 'block';
            submitButton.style.display = 'inline-block';
        } else {
            infoMessage.style.display = 'block';
            formCompleteMessage.style.display = 'none';
            submitButton.style.display = 'none';
        }
    }

    form.addEventListener('input', validateForm);
});
function updateTotalPrice() {
    var productSelect = document.getElementById('product');
    var selectedOption = productSelect.options[productSelect.selectedIndex];
    var totalAmount = 0;

    if (selectedOption.value === "all") {
        // Nếu chọn "Chọn hết", tính tổng tiền của tất cả sản phẩm
        var textPrices = document.querySelectorAll('.text-price');
        textPrices.forEach(function(textPrice) {
            var amount = parseInt(textPrice.innerText.replace(/\D/g, '')); // Lấy giá trị số từ text-price
            totalAmount += amount;
        });
    } else {
        // Nếu chọn một sản phẩm cụ thể
        var price = selectedOption.getAttribute('data-price');
        var quantity = selectedOption.getAttribute('data-quantity');
        totalAmount = (parseInt(price) || 0) * (parseInt(quantity) || 1);
    }

    // Hiển thị tổng số tiền
    document.getElementById('totalAmount').innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
}