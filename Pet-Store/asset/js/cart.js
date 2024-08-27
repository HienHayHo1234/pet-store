// Hàm thêm sản phẩm vào giỏ hàng
function addToPet(petId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../config/order.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            location.reload();
        }
    };

    xhr.send("action=add&pet_id=" + encodeURIComponent(petId));
    window("Đã thêm vào giỏ hàng!");
}

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(petId) {
    // Select the invoice-item div element associated with the petId
    const invoiceItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);
 
    if (invoiceItem) {
         // Xóa sản phẩm khỏi DOM
        invoiceItem.remove();
 
        // Gửi yêu cầu xóa sản phẩm khỏi giỏ hàng lên server
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../config/order.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log('Sản phẩm đã bị xóa thành công.');
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                }
            }
        };

        xhr.send("action=remove&pet_id=" + encodeURIComponent(petId));

        updateTotalAmount();

        // Kiểm tra nếu không còn sản phẩm nào thì reload lại trang
        const remainingItems = document.querySelectorAll('.invoice-item');
        if (remainingItems.length === 0) {
            location.reload();
        }
    }
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

// Khởi tạo tiền ban đầu
let totalAmountSelect = 0;
let totalAmountStart = 0;

// Hàm khởi tạo tổng tiền ban đầu khi trang tải
function initializeTotalAmount() {
    const totalAmountElement = document.querySelector('.total-amount');
    if (totalAmountElement) {
        totalAmountStart = parseInt(totalAmountElement.textContent.replace(/[^0-9]/g, ''), 10);
    }
}

// Khởi tạo tổng tiền ban đầu khi trang được tải
window.onload = function() {
    initializeTotalAmount();
};

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
}


function updateInvoice(itemId, quantity) {
    const invoiceItem = document.querySelector(`.totalPrice[data-id="${itemId}"]`);
    const pricePerItem = parseInt(invoiceItem.previousElementSibling.previousElementSibling.innerText.replace(/[^0-9]/g, ''), 10);

    const totalPrice = pricePerItem * quantity;
    invoiceItem.innerText = `Tổng giá: ${totalPrice.toLocaleString('vi-VN')}đ`;

    // Cập nhật tổng tiền trong .order-summary
    const allInvoiceItems = document.querySelectorAll('.totalPrice');
    let totalAmount = 0;

    allInvoiceItems.forEach(item => {
        const itemPrice = parseInt(item.innerText.replace(/[^0-9]/g, ''), 10);
        totalAmount += itemPrice;
    });

    totalAmountStart = totalAmount;
    updateTotalAmount(totalAmount);
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
        document.querySelector('.total-amount').innerText = selectedTotalAmount.toLocaleString('vi-VN') + 'đ';
    }
}



// Chọn tất cả select box
function toggleSelectAll(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('.checkbox-btn-cart');
    const totalAmountElement = document.querySelector('.total-amount');
    let totalAmount = 0;

    if (selectAllCheckbox.checked) {
        // Nếu chọn "Tất cả"
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) { // Chỉ chọn các checkbox chưa được chọn
                checkbox.checked = true; // Đánh dấu checkbox là đã chọn
                selectItem(checkbox.getAttribute('data-id')); // Thực hiện hành động thêm vào danh sách đã chọn
            } 
            const invoiceItem = document.querySelector(`.invoice-item[data-id="${checkbox.getAttribute('data-id')}"]`);
            if (invoiceItem) {
                const totalPriceElement = invoiceItem.querySelector('.totalPrice');
                const totalPrice = parseInt(totalPriceElement.textContent.replace(/[^0-9]/g, ''), 10);
                totalAmount += totalPrice; // Cộng dồn giá trị của tất cả các sản phẩm đã chọn
            }
        });
    } else {
        // Nếu bỏ chọn "Tất cả"
        checkboxes.forEach(checkbox => {
            checkbox.checked = false; // Bỏ chọn tất cả các checkbox
            selectItem(checkbox.getAttribute('data-id')); // Thực hiện hành động loại bỏ khỏi danh sách đã chọn
        });
        totalAmountSelect = 0;
        totalAmount = totalAmountStart; // Đặt tổng tiền về 0 khi bỏ chọn tất cả
    }

    // Cập nhật tổng tiền mới
    totalAmountElement.innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
    totalAmount = 0;
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
            // Thêm hiệu ứng rơi xuống
        }
    } else {
        // Xóa div khỏi truck
        if (invoiceCheckItem) {
            // Thêm hiệu ứng bay ngược lên

            // Xóa div khỏi truck khi hiệu ứng kết thúc
            invoiceCheckDiv.removeChild(invoiceCheckItem);

            // Kiểm tra nếu không có checkbox nào được chọn, ẩn truck
            const allCheckboxes = document.querySelectorAll('.checkbox-btn-cart');
            let anyChecked = false;
            allCheckboxes.forEach(cb => {
                if (cb.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                // Cập nhật tổng tiền mới vào phần tử .total-amount
            }
        }
    }
}

