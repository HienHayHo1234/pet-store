<?php
$pageuser = isset($_GET['page']) ? $_GET['page'] : 'profile';

switch ($pageuser) {

    case 'orders':
        require_once 'orders.php';
        break;
    case 'change_password':
        require_once 'change_password.php';
        break;
    case 'address_book':
        require_once 'address_book.php';
        break;
    default:
        require_once 'profile.php';
        break;
}
?>