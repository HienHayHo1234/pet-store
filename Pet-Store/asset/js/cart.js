// Hàm thêm sản phẩm vào giỏ hàng
function addToPet(petId) {
    sendCartRequest('add', petId);
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(petId) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        sendCartRequest('remove', petId);
    }
}

// Hàm gửi yêu cầu đến server
function sendCartRequest(action, petId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../config/order.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                if (action === 'remove') {
                    // Hiển thị thông báo
                    showPopup(response.message, 'info');
                    // Xóa phần tử khỏi DOM
                    removeCartItem(petId);
                } else {
                    showPopup(response.message, 'success');
                    updateCartDisplay(); // Cập nhật hiển thị giỏ hàng nếu cần
                }
            } else {
                showPopup("Có lỗi xảy ra: " + response.message, "error");
            }
        }
    };

    xhr.send("action=" + action + "&pet_id=" + encodeURIComponent(petId));
}

// Hàm xóa phần tử khỏi DOM
function removeCartItem(petId) {
    var cartItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);
    if (cartItem) {
        cartItem.remove();
    }
    
    // Xóa khỏi invoice check nếu có
    var invoiceCheckItem = document.querySelector(`.invoice-check-item[data-id="${petId}"]`);
    if (invoiceCheckItem) {
        invoiceCheckItem.remove();
    }

    // Kiểm tra xem còn sản phẩm nào trong giỏ hàng không
    var remainingItems = document.querySelectorAll('.invoice-item');
    if (remainingItems.length === 0) {
        // Nếu không còn sản phẩm nào, tải lại trang sau 1 giây
        location.reload();

    } else {
        // Nếu còn sản phẩm, cập nhật tổng giá và số lượng
        updateCartSummary();
    }
}

// Hàm cập nhật tổng giá và số lượng
function updateCartSummary() {
    // Cập nhật tổng số lượng
    var totalQuantity = 0;
    var quantityElements = document.querySelectorAll('.quantity');
    quantityElements.forEach(function(element) {
        totalQuantity += parseInt(element.textContent);
    });
    var totalQuantityElement = document.querySelector('.total-quantity');
    if (totalQuantityElement) {
        totalQuantityElement.textContent = totalQuantity;
    }

    // Cập nhật tổng giá
    var totalAmount = 0;
    var priceElements = document.querySelectorAll('.totalPrice');
    priceElements.forEach(function(element) {
        totalAmount += parseInt(element.textContent.replace(/[^0-9]/g, ''));
    });
    var totalAmountElement = document.querySelector('.total-amount');
    if (totalAmountElement) {
        totalAmountElement.textContent = totalAmount.toLocaleString('vi-VN') + 'đ';
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng (nếu có hiển thị)
    var cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalQuantity;
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
    }, 2000);
}

 // cập nhật lại số lượng
 function updateQuantity(itemId, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../config/update-quantity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`id=${itemId}&quantity=${quantity}`);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
            } else {
                alert('Lỗi: ' + response.message);
            }
        }
    };
}

// Cập nhật tổng tiền
function updateTotalAmount() {
    // Cập nhật tổng tiền trong .order-summary
    const allInvoiceItems = document.querySelectorAll('.totalPrice');
    let totalAmount = 0;

    allInvoiceItems.forEach(item => {
        const itemPrice = parseInt(item.innerText.replace(/[^0-9]/g, ''), 10);
        totalAmount += itemPrice;
    });

    const totalAmountElement = document.querySelector('.total-amount');

    if (totalAmountElement) {
        // Cập nhật tổng tiền mới vào phần tử .total-amount
        totalAmountElement.innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
    }
}

function updateTotalAmountSelected() {
    let totalAmount = 0;
    let checkboxes = document.querySelectorAll('.checkbox-btn-cart:checked');

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            let invoiceItem = checkbox.closest('.invoice-item');
            let totalPrice = parseFloat(invoiceItem.querySelector('.totalPrice').textContent.replace(/\D/g, '')); // Lấy tổng giá từ .totalPrice
            totalAmount += totalPrice;
        }
    });

    document.querySelector('.total-amount').innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
}

// Đảm bảo chỉ gắn sự kiện một lần cho các nút cộng trừ
document.addEventListener('DOMContentLoaded', function() {
    const minusButtons = document.querySelectorAll('.quantity-btn.minus');
    const plusButtons = document.querySelectorAll('.quantity-btn.plus');

    minusButtons.forEach(button => {
        button.removeEventListener('click', handleMinusClick);
        button.addEventListener('click', handleMinusClick);
    });

    plusButtons.forEach(button => {
        button.removeEventListener('click', handlePlusClick);
        button.addEventListener('click', handlePlusClick);
    });
});

function handleMinusClick() {
    const itemId = this.getAttribute('data-id');
    const quantitySpan = this.nextElementSibling;
    let quantity = parseInt(quantitySpan.textContent, 10);

    if (quantity > 1) {
        quantity--;
        quantitySpan.textContent = quantity;
        updateQuantity(itemId, quantity);
        updateInvoice(itemId, quantity);
        updateInvoiceCheck(itemId);
    }
}

function handlePlusClick() {
    const itemId = this.getAttribute('data-id');
    const quantitySpan = this.previousElementSibling;
    let quantity = parseInt(quantitySpan.textContent, 10);

    quantity++;
    quantitySpan.textContent = quantity;
    updateQuantity(itemId, quantity);
    updateInvoice(itemId, quantity);
    updateInvoiceCheck(itemId);
}

function updateInvoice(itemId, quantity) {
    const invoiceItem = document.querySelector(`.totalPrice[data-id="${itemId}"]`);
    const pricePerItem = parseInt(invoiceItem.previousElementSibling.previousElementSibling.innerText.replace(/[^0-9]/g, ''), 10);

    const totalPrice = pricePerItem * quantity;
    invoiceItem.innerText = `Tổng giá: ${totalPrice.toLocaleString('vi-VN')}đ`;

    // Kiểm tra nếu không có checkbox nào được chọn
    const allCheckboxes = document.querySelectorAll('.checkbox-btn-cart');
            
    let anyChecked = false;
    allCheckboxes.forEach(cb => {
        if (cb.checked) {
            anyChecked = true;
        }
    });

    if (!anyChecked) {
        // Cập nhật tổng tiền mới vào phần tử .total-amount
        updateTotalAmount();
    } else {
        updateTotalAmountSelected();
    }
}
    

function updateInvoiceCheck(petId) {
    // Cập nhật trong invoice check
    const invoiceCheckItem = document.querySelector(`.invoice-check-item[data-id="${petId}"]`);
    // Select the totalPrice element based on the item ID
    const invoiceItemPrice = document.querySelector(`.totalPrice[data-id="${petId}"]`);
    const totalPrice = parseInt(invoiceItemPrice.innerText.replace(/[^0-9]/g, ''), 10);

    if (invoiceCheckItem) {
        // Tìm phần tử chứa giá trong invoice-check-item
        const priceParagraph = invoiceCheckItem.querySelector('p:last-child'); // Giả sử giá nằm ở phần tử p cuối cùng
        
        // Cập nhật nội dung của phần tử chứa giá
        if (priceParagraph) {
            priceParagraph.innerText = 'Giá: ' + totalPrice.toLocaleString('vi-VN') + 'đ';
        }
    }
}



// Chọn tất cả select box
function toggleSelectAll(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.checkbox-btn-cart');

    if (selectAllCheckbox.checked) {
        // Nếu chọn "Tất cả"
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) { // Chỉ chọn các checkbox chưa được chọn
                checkbox.checked = true; // Đánh dấu checkbox là đã chọn
                selectItem(checkbox.getAttribute('data-id')); // Thực hiện hành động thêm vào danh sách đã chọn
            } 
        });

        updateTotalAmountSelected();
    } else {
        // Nếu bỏ chọn "Tất cả"
        checkboxes.forEach(checkbox => {
            checkbox.checked = false; // Bỏ chọn tất cả các checkbox
            selectItem(checkbox.getAttribute('data-id')); // Thực hiện hành động loại bỏ khỏi danh sách đã chọn
        });

        updateTotalAmount();
    }
}

// hàm tạo item selected
function selectItem(petId) {
    const invoiceCheckDiv = document.querySelector('.container-invoice-check');
    const checkbox = document.querySelector(`.checkbox-btn-cart[data-id="${petId}"]`);
    const invoiceItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);
    
    // trỏ tới giá trị pet được nhấn
    const imageElement = invoiceItem.querySelector('.imgInvoice');
    const nameElement = invoiceItem.querySelector('.name-pet-cart');
    const totalPriceElement = invoiceItem.querySelector('.totalPrice');

    const imageSrc = imageElement.src;
    const name = nameElement.textContent;
    const totalPrice = parseInt(totalPriceElement.textContent.replace(/[^0-9]/g, ''), 10);
    // kiểm tra đã có chưa
    const invoiceCheckItem = document.querySelector(`.invoice-check-item[data-id="${petId}"]`);

    if (checkbox.checked) {
        if (!invoiceCheckItem) {
            // Tạo phần tử div chứa hình ảnh và thông tin sản phẩm
            const invoiceCheckItem = document.createElement('div');
            invoiceCheckItem.className = 'invoice-check-item';
            invoiceCheckItem.setAttribute('data-id', petId);

            // Tạo phần tử hình ảnh và thêm vào div
            const image = document.createElement('img');
            image.src = imageSrc;
            image.alt = name;

            // Tạo phần tử chứa tên sản phẩm
            const nameParagraph = document.createElement('p');
            nameParagraph.textContent = `Sản phẩm: ${name}`;

            // Tạo phần tử chứa tổng giá sản phẩm
            const totalPriceParagraph = document.createElement('p');
            totalPriceParagraph.textContent = 'Giá: ' + totalPrice.toLocaleString('vi-VN') + 'đ';

            // Thêm các phần tử vào trong invoiceCheckItem
            invoiceCheckItem.appendChild(image);
            invoiceCheckItem.appendChild(nameParagraph);
            invoiceCheckItem.appendChild(totalPriceParagraph);

            // Thêm invoiceCheckItem vào trong container truck
            invoiceCheckDiv.appendChild(invoiceCheckItem);
            invoiceCheckDiv.style.display = 'flex';

            updateTotalAmountSelected();
        }
    } else {
        // Xóa div khỏi truck
        if (invoiceCheckItem) {
            // nút chọn tất cả
            const checkBoxAll = document.querySelector('.checkbox-all-btn-cart');

            checkBoxAll.checked = false;

            // Xóa div khỏi truck khi hiệu ứng kết thúc
            invoiceCheckDiv.removeChild(invoiceCheckItem);

            // cập nhật lại tổng tiền
            updateTotalAmountSelected();

            // Kiểm tra nếu không có checkbox nào được chọn
            const allCheckboxes = document.querySelectorAll('.checkbox-btn-cart');
            
            let anyChecked = false;
            allCheckboxes.forEach(cb => {
                if (cb.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                // Cập nhật tổng tiền mới vào phần tử .total-amount
                updateTotalAmount();
            }
        }
    }
}

