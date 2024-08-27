function openDetailModal(petId) {
    fetch(`DetailPet.php?action=getPetDetails&id=${petId}`)
        .then(response => response.json())
        .then(data => {
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
        })
        .catch(error => console.error('Error:', error));
}

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
    document.querySelector('#modal .close').addEventListener('click', function () {
        document.querySelector('#modal').style.display = 'none';
    });
});
