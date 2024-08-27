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
    
    alert("Sản phẩm đã thêm vào giỏ hàng")

}








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
                // Cập nhật tổng số tiền
                document.getElementById('totalAmount').textContent = response.totalAmount;
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
}


// Định nghĩa hàm selectItem ở phạm vi toàn cục
function selectItem(petId) {
    const truck = document.getElementById('truck');
    const checkbox = document.querySelector(`.checkbox-btn-cart[data-id="${petId}"]`);
    const imageElement = document.querySelector(`.invoice-item[data-id="${petId}"] .imgInvoice`);

    if (!imageElement) {
        console.error(`Hình ảnh với petId ${petId} không được tìm thấy.`);
        return;
    }

    const imageSrc = imageElement.src;

    if (checkbox.checked) {
        // Tạo phần tử hình ảnh và thêm vào truck
        const image = document.createElement('img');
        image.src = imageSrc;
        image.className = 'truck-image'; // Thêm lớp CSS cho hình ảnh
        truck.appendChild(image);
        truck.style.display = 'flex'; // Hiển thị truck

        // Thêm hiệu ứng rơi xuống
        setTimeout(() => {
            image.classList.add('fall');
        }, 10);
    } else {
        // Xóa hình ảnh khỏi truck
        const imageToRemove = document.querySelector(`.truck-image[src="${imageSrc}"]`);
        if (imageToRemove) {
            // Thêm hiệu ứng bay ngược lên
            imageToRemove.classList.add('fly-up');

            // Xóa hình ảnh khỏi truck khi hiệu ứng kết thúc
            setTimeout(() => {
                truck.removeChild(imageToRemove);

                // Kiểm tra nếu không có checkbox nào được chọn, ẩn truck
                const allCheckboxes = document.querySelectorAll('.checkbox-btn-cart');
                let anyChecked = false;
                allCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        anyChecked = true;
                    }
                });

                if (!anyChecked) {
                    truck.style.display = 'none'; // Ẩn truck nếu không còn checkbox nào được chọn
                }
            }, 500); // Thời gian của hiệu ứng bay ngược lên
        }
    }
}
