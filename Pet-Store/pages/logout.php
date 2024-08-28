<?php
session_start();

// Xóa tất cả các biến phiên
session_unset();

// Hủy phiên
session_destroy();

// Chuyển hướng về trang chính
header("Location: index.php");
exit();
?>
