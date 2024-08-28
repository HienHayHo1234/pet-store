<?php
$page = isset($_GET['page']) ? $_GET['page'] : '';

switch ($page) {
    case "cat":
        require_once "cat.php";
        break;
    case "dog":
        require_once "dog.php";
        break;
    case "parrot":
        require_once "parrot.php";
        break;
    case "cart":
        require_once "cart.php";
        break;
    case "formAdmin":
        require_once "formAdmin.php";
        break;
    case "admin":
        require_once "admin.php";
        break;
    case "search":
        require_once "ketquatimkiem.php";
        break;
    case "about":
        require_once "about.php";
        break;
    case "profile":
        require_once "profile.php";
        break;
    default:
        require_once "start.php";
        break;
}