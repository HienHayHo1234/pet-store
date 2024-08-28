document.addEventListener('DOMContentLoaded', function () {
    // Lấy tất cả các nút 'Chi tiết'
    const detailButtons = document.querySelectorAll('.view-details');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const petId = this.dataset.id;
            console.log('Clicked button for pet ID:', petId); // Debug log

            // Gọi hàm openDetailModal để mở modal và hiển thị chi tiết
            openDetailModal(petId);
        });
    });

    // Đóng modal khi nhấp vào nút đóng
    document.querySelector('#modal .close-detail').addEventListener('click', function () {
        document.querySelector('#modal').style.display = 'none';
    });

    // Đóng modal khi nhấp vào vùng ngoài modal
    window.addEventListener('click', function (event) {
        if (event.target == document.querySelector('#modal')) {
            document.querySelector('#modal').style.display = 'none';
        }
    });
});

function openDetailModal(petId) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `DetailPet.php?action=getPetDetails&id=${petId}`, true);
    xhr.send();

    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                console.log('Data received:', data); // Debug log
                if (data.error) {
                    alert(data.error);
                } else {
                    // Cập nhật thông tin vào modal
                    document.querySelector('#modal .modal-title').innerText = data.name;
                    document.querySelector('#modal .modal-img').src = data.urlImg;
                    document.querySelector('#modal .modal-price').innerText = `Giá: ${data.price.toLocaleString()}đ`;
                    document.querySelector('#modal .modal-sale-price').innerText = `Giá khuyến mãi: ${data.priceSale.toLocaleString()}đ`;
                    document.querySelector('#modal .modal-quantity').innerText = `Số lượng còn lại: ${data.quantity}`;
                    document.querySelector('#modal .modal-description').innerText = `Mô tả: ${data.description}`;

                    // Hiển thị modal
                    document.querySelector('#modal').style.display = 'block';
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                alert('Lỗi khi xử lý dữ liệu từ server.');
            }
        } else {
            console.error('Request failed with status code:', xhr.status);
            alert('Lỗi khi gửi yêu cầu. Status code: ' + xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error('Network error occurred');
        alert('Có lỗi xảy ra khi gửi yêu cầu.');
    };
}