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
    alert("Đã thêm vào giỏ hàng!");
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

// Hàm xóa sản phẩm khỏi giỏ hàng
function removeFromCart(petId) {
    // Select the invoice-item div element associated with the petId
    const invoiceItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);

    if (invoiceItem) {
        // Lấy giá trị priceSale và quantity hiện tại
        const priceSale = parseInt(invoiceItem.querySelector('.invoice-text>p').innerText.replace(/[^0-9]/g, ''), 10);
        const quantity = parseInt(invoiceItem.querySelector('.count span').innerText, 10);
        console.log(priceSale);
        // Tính toán số tiền của sản phẩm bị xóa
        const amountToSubtract = priceSale * quantity;

        // Cập nhật tổng tiền
        updateTotalAmount(amountToSubtract);

        // Xóa sản phẩm khỏi DOM
        invoiceItem.remove();

        // Gửi yêu cầu xóa sản phẩm khỏi giỏ hàng lên server
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../config/order.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {

                try {
                    // Cố gắng phân tích phản hồi JSON
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log('Sản phẩm đã bị xóa thành công.');

                        // Kiểm tra xem có còn sản phẩm nào trong giỏ hàng không
                        const remainingItems = document.querySelectorAll('.invoice-item').length;
                        if (remainingItems === 0) {
                            // Xóa cả phần tổng tiền và nút đặt hàng nếu không còn sản phẩm nào
                            const orderSummary = document.querySelector('.order-summary');
                            if (orderSummary) {
                                orderSummary.remove();
                            }

                            // Cập nhật thông báo không có sản phẩm nào
                            const containerInvoiceList = document.querySelector('.container-invoice-list');
                            if (containerInvoiceList) {
                                containerInvoiceList.innerHTML = '<p style="font-size: larger">Không có sản phẩm nào trong giỏ hàng!</p>';
                            }
                        }
                    } else {
                        alert('Lỗi: ' + response.message);
                        // Nếu có lỗi, có thể muốn hoàn tác việc xóa khỏi DOM
                    }
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    alert('Lỗi: Phản hồi từ máy chủ không hợp lệ.');
                }
            }
        };

        xhr.send("action=remove&pet_id=" + encodeURIComponent(petId));
    }
}

// Cập nhật tổng tiền sau khi xóa sản phẩm
function updateTotalAmount(amountToSubtract) {
    const totalAmountElement = document.querySelector('.total-amount');
    
    // Lấy giá trị tổng tiền hiện tại
    let totalAmount = parseInt(totalAmountElement.innerText.replace(/[^0-9]/g, ''), 10);
    
    // Trừ số tiền của sản phẩm bị xóa
    totalAmount -= amountToSubtract;

    // Cập nhật tổng tiền mới vào phần tử .total-amount
    if (totalAmountElement) {
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

    document.querySelector('.total-amount').innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
    
    updateInvoiceCheck(itemId);
}
    

function updateInvoiceCheck(petId) {
    // Cập nhật trong invoice check
    const invoiceCheckItem = document.querySelector(`.invoice-check-item[data-id="${petId}"]`);

    if (invoiceCheckItem) {
        // Tìm phần tử chứa giá trong invoice-check-item
        const priceParagraph = invoiceCheckItem.querySelector('p:last-child'); // Giả sử giá nằm ở phần tử p cuối cùng
        
        // Cập nhật nội dung của phần tử chứa giá
        if (priceParagraph) {
            priceParagraph.innerText = 'Giá: ' + totalPrice.toLocaleString('vi-VN') + 'đ';
        }

        // Kiểm tra số lượng checkbox được chọn
        const selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        let selectedTotalAmount = 0;

        // Lặp qua các checkbox đã được chọn và tính tổng tiền
        selectedCheckboxes.forEach(checkbox => {
            const selectedItemId = checkbox.getAttribute('data-id');
            const selectedInvoiceItem = document.querySelector(`.totalPrice[data-id="${selectedItemId}"]`);
            const selectedItemPrice = parseInt(selectedInvoiceItem.innerText.replace(/[^0-9]/g, ''), 10);
            selectedTotalAmount = selectedItemPrice;
        });

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
            checkbox.checked = true; // Đánh dấu checkbox là đã chọn
            selectItem(checkbox.getAttribute('data-id')); // Thực hiện hành động thêm vào danh sách đã chọn
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
        totalAmount = totalAmountStart; // Đặt tổng tiền về 0 khi bỏ chọn tất cả
    }

    // Cập nhật tổng tiền mới
    totalAmountElement.innerText = totalAmount.toLocaleString('vi-VN') + 'đ';
}



// Định nghĩa hàm selectItem ở phạm vi toàn cục
function selectItem(petId) {
    const invoiceCheckDiv = document.querySelector('.container-invoice-check');
    const checkbox = document.querySelector(`.checkbox-btn-cart[data-id="${petId}"]`);
    const invoiceItem = document.querySelector(`.invoice-item[data-id="${petId}"]`);
    
    const imageElement = invoiceItem.querySelector('.imgInvoice');
    const nameElement = invoiceItem.querySelector('.name-pet-cart');
    const totalPriceElement = invoiceItem.querySelector('.totalPrice');

    const imageSrc = imageElement.src;
    const name = nameElement.textContent;
    const totalPrice = parseInt(totalPriceElement.textContent.replace(/[^0-9]/g, ''), 10);

    if (checkbox.checked) {
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
        invoiceCheckDiv.style.display = 'flex'; // Hiển thị truck

        // Cộng giá trị của sản phẩm đã chọn vào tổng tiền
        totalAmountSelect += totalPrice;

        // Cập nhật tổng tiền mới vào phần tử .total-amount
        document.querySelector('.total-amount').innerText = totalAmountSelect.toLocaleString('vi-VN') + 'đ';

        // Thêm hiệu ứng rơi xuống
        setTimeout(() => {
            invoiceCheckItem.classList.add('fall');
        }, 10);
    } else {
        // Xóa div khỏi truck
        const itemToRemove = invoiceCheckDiv.querySelector(`.invoice-check-item[data-id="${petId}"]`);
        if (itemToRemove) {
            // Trừ giá trị của sản phẩm bỏ chọn khỏi tổng tiền
            totalAmountSelect -= totalPrice;

            // Thêm hiệu ứng bay ngược lên
            itemToRemove.classList.add('fly-up');

            // Xóa div khỏi truck khi hiệu ứng kết thúc
            invoiceCheckDiv.removeChild(itemToRemove);

            document.querySelector('.total-amount').innerText = totalAmountSelect.toLocaleString('vi-VN') + 'đ';

            // Kiểm tra nếu không có checkbox nào được chọn, ẩn truck
            const allCheckboxes = document.querySelectorAll('.checkbox-btn-cart');
            let anyChecked = false;
            allCheckboxes.forEach(cb => {
                if (cb.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                // xóa check tất cả
                document.querySelector('.checkbox-all-btn-cart').checked = false;
                // Cập nhật tổng tiền mới vào phần tử .total-amount
                document.querySelector('.total-amount').innerText = totalAmountStart.toLocaleString('vi-VN') + 'đ';
            }
        }
    }
}

