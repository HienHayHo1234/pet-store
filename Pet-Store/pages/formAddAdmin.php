<main class="item-main">
    <div id="add-form">
        <h1>Thêm Sản Phẩm Mới</h1>
        <form id="pet-form" action="../config/upload_form.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pet-id">ID:</label>
                <input type="text" id="pet-id" name="pet-id" required>
            </div>
            <div class="form-group">
                <label for="pet-name">Tên:</label>
                <input type="text" id="pet-name" name="pet-name" required>
            </div>
            <div class="form-group">
                <label for="pet-price">Giá:</label>
                <input type="number" id="pet-price" name="pet-price" required>
            </div>
            <div class="form-group">
                <label for="pet-price-sale">Giá Khuyến Mãi:</label>
                <input type="number" id="pet-price-sale" name="pet-price-sale" required>
            </div>
            <div class="form-group">
                <label for="pet-gender">Giới Tính:</label>
                <select id="pet-gender" name="pet-gender" required>
                    <option value="">Chưa xác định</option>
                    <option value="1">Nam</option>
                    <option value="0">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-quantity">Số Lượng:</label>
                <input type="number" id="pet-quantity" name="pet-quantity" required>
            </div>
            <div class="form-group">
                <label for="pet-idLoai">Danh Mục:</label>
                <select id="pet-idLoai" name="pet-idLoai" required>
                    <!-- Thay thế các tùy chọn dưới đây bằng các danh mục thực tế từ cơ sở dữ liệu -->
                    <option value="cat">Mèo</option>
                    <option value="dog">Chó</option>
                    <option value="parrot">Vẹt</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pet-image">Hình Ảnh:</label>
                <input type="file" id="pet-image" name="pet-image" required>
            </div>
            <div class="form-group">
                <label for="pet-description">Mô Tả:</label>
                <textarea id="pet-description" name="pet-description" rows="4" required></textarea>
            </div>
            <button class="btn-submit-admin" type="submit">Thêm Sản Phẩm</button>
        </form>
    </div>
</main>