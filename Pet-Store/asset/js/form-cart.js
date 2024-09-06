function showOrderForm(productId) {
    // Hiển thị form đặt hàng
    var orderForm = document.getElementById('orderForm');
    orderForm.style.display = '';

    // Thêm hoặc cập nhật trường hidden input cho pet_id
    var petIdInput = document.getElementById('pet_id');
    if (!petIdInput) {
        petIdInput = document.createElement('input');
        petIdInput.type = 'hidden';
        petIdInput.id = 'pet_id';
        petIdInput.name = 'pet_id';
        orderForm.appendChild(petIdInput);
    }
    petIdInput.value = productId;

    // Gán giá trị productId vào hidden input và cập nhật tổng giá
    updateTotalPriceForm(productId);

    // Cuộn trang đến form đặt hàng
    orderForm.scrollIntoView({
        behavior: 'smooth'
    });
}
function showOrderAllForm() {
    // Hiển thị form đặt hàng
    var orderForm = document.getElementById('orderForm');
    orderForm.style.display = '';
    // Gán giá trị productId vào hidden input
    updateTotalPriceAllForm();
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

        if (name && address && phone) {
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
function updateTotalPriceForm(petId) {
    // Select the invoice item based on the item ID
    const invoiceItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);

    if (invoiceItem) {
        // Extract the pet name
        const nameElement = invoiceItem.querySelector('.name-pet-cart');
        const name = nameElement ? nameElement.textContent : 'Unknown Pet';

        // Update the name in the form
        const nameFormElement = document.getElementById('name-invoice-form');
        const nameInFormElement = document.querySelector('.total-amount.nameInForm');

        nameInFormElement.style.display = '';
        nameFormElement.innerText = name;

        // Select and parse the total price
        const invoiceItemPrice = invoiceItem.querySelector('.totalPrice');
        if (invoiceItemPrice) {
            const totalPrice = parseInt(invoiceItemPrice.innerText.replace(/[^0-9]/g, ''), 10);

            // Update the total amount in the form
            document.getElementById('total-amount-form').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
        }
    } else {
        console.error(`No invoice item found for petId: ${petId}`);
    }
}

function updateTotalPriceAllForm() {
    // Select the total amount element
    const totalAmountElement = document.querySelector('.order-summary .total-amount');

    if (totalAmountElement) {
        // Extract the total price value
        const totalPriceText = totalAmountElement.innerText.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        const totalPrice = parseInt(totalPriceText, 10); // Convert to integer

        // Update the total amount in the form
        document.getElementById('total-amount-form').innerText = totalPrice.toLocaleString('vi-VN') + 'đ';
    }
}

// Hàm gửi yêu cầu đến server
function submitOrder() {
    const form = document.getElementById('orderFormElement');
    const formData = new FormData(form);
    
    // Thêm action 'addtoorder' vào formData
    formData.append('action', 'addtoorder');
    
    // Thêm pet_id vào formData (giả sử bạn có một input hidden chứa pet_id)
    const petId = document.getElementById('pet_id').value;
    formData.append('pet_id', petId);
    
    // Thêm tổng số tiền vào formData
    formData.append('total_amount', document.getElementById('total-amount-form').innerText);
    
    fetch('../config/order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Có thể thêm code để xóa giỏ hàng hoặc chuyển hướng người dùng
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi gửi đơn hàng.');
    });
}
