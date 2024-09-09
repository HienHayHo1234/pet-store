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
        orderForm.querySelector('form').appendChild(petIdsInput);
    }
    petIdsInput.value = JSON.stringify(petIds);

    // Cập nhật tổng giá trị đơn hàng
    updateTotalPriceAllForm();

    // Cuộn trang đến form đặt hàng
    orderForm.scrollIntoView({
        behavior: 'smooth'
    });
}

function closeOrderForm() {
    // Ẩn form
    document.getElementById('orderForm').style.display = 'none';
}
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('orderFormElement');
    const submitButton = document.querySelector('.btn-submit');

    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked');
        const submitButton = document.querySelector('.btn-submit');

        if (name && address && phone && paymentMethod) {
            // Tất cả các trường đã được điền
            if (submitButton) {
                submitButton.style.display = 'block';
                submitButton.disabled = false;
            }
        } else {
            // Còn trường chưa được điền
            if (submitButton) {
                submitButton.style.display = 'none'; // Ẩn nút khi chưa điền đủ thông tin
                submitButton.disabled = true;
            }
        }
    }

    // Thêm event listener cho các trường input
    document.getElementById('name').addEventListener('input', validateForm);
    document.getElementById('address').addEventListener('input', validateForm);
    document.getElementById('phone').addEventListener('input', validateForm);
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', validateForm);
    });

    // Gọi validateForm lần đầu để set trạng thái ban đầu
    validateForm();

    form.addEventListener('input', validateForm);

    // Đảm bảo rằng submitButton tồn tại trước khi thêm event listener
    if (submitButton) {
        submitButton.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn chặn form submit mặc định
            submitOrder(); // Gọi hàm submitOrder khi nút được nhấn
        });
    }
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
    var totalAmount = calculateTotalAmountSelected();
    document.getElementById('total-amount-form').innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
}

function calculateTotalAmountSelected() {
    var checkboxes = document.querySelectorAll('.checkbox-btn-cart');
    
    // Nếu không có checkbox nào, tính tổng tất cả các mục
    if (checkboxes.length === 0) {
        return calculateTotalAmount();
    }
    
    var selectedCheckboxes = document.querySelectorAll('.checkbox-btn-cart:checked');
    
    // Nếu không có checkbox nào được chọn, cũng tính tổng tất cả các mục
    if (selectedCheckboxes.length === 0) {
        return calculateTotalAmount();
    }
    
    // Nếu có checkbox được chọn, tính tổng các mục đã chọn
    var total = 0;
    selectedCheckboxes.forEach(function(checkbox) {
        var invoiceItem = checkbox.closest('.invoice-item');
        if (invoiceItem) {
            var priceElement = invoiceItem.querySelector('.totalPrice');
            if (priceElement) {
                var price = parseInt(priceElement.innerText.replace(/[^\d]/g, ''), 10);
                total += price;
            }
        }
    });
    
    return total;
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
    
    formData.append('action', 'addtoorder');
    
    // Kiểm tra xem có pet_ids hay không
    const petIdsInput = document.getElementById('pet_ids');
    if (petIdsInput && petIdsInput.value) {
        formData.append('pet_ids', petIdsInput.value);
    } else {
        // Nếu không có pet_ids, thử lấy từ pet_id (cho trường hợp đặt hàng một sản phẩm)
        const petIdInput = document.getElementById('pet_id');
        if (petIdInput && petIdInput.value) {
            formData.append('pet_ids', JSON.stringify([petIdInput.value]));
        } else {
            console.error('Không tìm thấy pet_ids hoặc pet_id');
            alert('Không có sản phẩm nào được chọn để đặt hàng.');
            return;
        }
    }
    
    const totalAmount = document.getElementById('total-amount-form').innerText;
    formData.append('total_amount', totalAmount.replace(/[^\d]/g, ''));
    
    fetch('../config/order_config.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Lưu thông báo vào localStorage
            localStorage.setItem('orderMessage', data.message);
            localStorage.setItem('orderSuccess', 'true');
            
            // Đóng form đặt hàng và reload trang
            closeOrderForm();
            location.reload();
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
    const checkboxes = document.querySelectorAll('.checkbox-btn-cart');

    if (checkboxes && checkboxes.length > 0) {
        // Nếu có checkboxes, chỉ lấy các pet đã được chọn
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                var petId = checkbox.getAttribute('data-id');
                if (petId) {
                    petIds.push(petId);
                }
            }
        });
    } else {
        // Nếu không có checkboxes, lấy tất cả pet trong giỏ hàng
        const cartItems = document.querySelectorAll('.invoice-item');
        cartItems.forEach(function(item) {
            var petId = item.getAttribute('data-id');
            if (petId) {
                petIds.push(petId);
            }
        });
    }

    // Kiểm tra nếu không có pet nào được chọn
    if (petIds.length === 0) {
        console.warn('Không có pet nào được chọn');
    }

    return petIds;
}

function removeAllFromCartUI() {
    const cartItems = document.querySelectorAll('.invoice-item');
    cartItems.forEach(item => item.remove());
}

// Hàm kiểm tra và hiển thị popup sau khi trang đã tải
function checkAndShowOrderPopup() {
    const message = localStorage.getItem('orderMessage');
    const success = localStorage.getItem('orderSuccess');
    
    if (message) {
        showPopup(message, success === 'true' ? 'success' : 'error');
        
        // Xóa thông tin từ localStorage sau khi đã hiển thị
        localStorage.removeItem('orderMessage');
        localStorage.removeItem('orderSuccess');
    }
}

// Hàm hiển thị cửa sổ popup
function showPopup(message, type = "success") {
    var popup = document.getElementById('popup-notification');
    var popupMessage = document.getElementById('popup-message');
    popupMessage.textContent = message;
    popup.className = 'popup-notification ' + type;
    popup.style.display = 'block';

    // Tự động ẩn popup sau 2 giây
    setTimeout(function() {
        popup.style.display = 'none';
    }, 1000);
}

// Thêm event listener để kiểm tra và hiển thị popup sau khi trang đã tải
window.addEventListener('load', checkAndShowOrderPopup);
