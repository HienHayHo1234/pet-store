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

    // Lấy tất cả petId từ các sản phẩm trong giỏ hàng
    var petIds = getAllPetIds();

    // Gán danh sách petId vào một input hidden
    var petIdsInput = document.getElementById('pet_ids');
    if (!petIdsInput) {
        petIdsInput = document.createElement('input');
        petIdsInput.type = 'hidden';
        petIdsInput.id = 'pet_ids';
        petIdsInput.name = 'pet_ids';
        orderForm.appendChild(petIdsInput);
    }
    petIdsInput.value = JSON.stringify(petIds);

    // Cập nhật tổng giá trị đơn hàng
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
    var totalAmount = calculateTotalAmount();
    document.getElementById('total-amount-form').innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
}

function calculateTotalAmount() {
    var total = 0;
    var priceElements = document.querySelectorAll('.invoice-item .totalPrice');
    priceElements.forEach(function(element) {
        var price = parseInt(element.innerText.replace(/[^\d]/g, ''), 10);
        total += price;
    });
    return total;
}

// Hàm gửi yêu cầu đến server
function submitOrder() {
    const form = document.getElementById('orderFormElement');
    const formData = new FormData(form);
    
    // Thêm action 'addtoorder' vào formData
    formData.append('action', 'addtoorder');
    
    // Lấy danh sách pet_ids từ input hidden
    const petIds = JSON.parse(document.getElementById('pet_ids').value);
    formData.append('pet_ids', JSON.stringify(petIds));
    
    // Thêm tổng số tiền vào formData
    const totalAmount = document.getElementById('total-amount-form').innerText;
    formData.append('total_amount', totalAmount.replace(/[^\d]/g, '')); // Chỉ lấy số
    
    fetch('../config/order_config.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Xóa tất cả sản phẩm khỏi giỏ hàng client-side
            removeAllFromCartUI();
            // Đóng form đặt hàng
            closeOrderForm();
            // Cập nhật tổng giá trị giỏ hàng
            updateTotalCartAmount();
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Đã xảy ra lỗi khi gửi đơn hàng.');
    });
}

function removeFromCartUI(petId) {
    const cartItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);
    if (cartItem) {
        cartItem.remove();
    }
}

function closeOrderForm() {
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.style.display = 'none';
    }
}

function updateTotalCartAmount() {
    const totalAmountElement = document.querySelector('.order-summary .total-amount');
    const cartItems = document.querySelectorAll('.invoice-item');
    let newTotal = 0;

    cartItems.forEach(item => {
        const priceElement = item.querySelector('.totalPrice');
        if (priceElement) {
            const price = parseInt(priceElement.innerText.replace(/[^\d]/g, ''), 10);
            newTotal += price;
        }
    });

    if (totalAmountElement) {
        totalAmountElement.innerText = newTotal.toLocaleString('vi-VN') + 'đ';
    }
}

function getAllPetIds() {
    var petIds = [];
    var cartItems = document.querySelectorAll('.invoice-item');
    cartItems.forEach(function(item) {
        var petId = item.getAttribute('data-id');
        if (petId) {
            petIds.push(petId);
        }
    });
    return petIds;
}

function removeAllFromCartUI() {
    const cartItems = document.querySelectorAll('.invoice-item');
    cartItems.forEach(item => item.remove());
}
