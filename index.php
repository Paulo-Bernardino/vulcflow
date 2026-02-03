<?php
ob_start();
require_once __DIR__ . '/Config/init.php';

if (isset($_GET['logoff'])) {
    logoutUser();
    header("Location: " . BASE_URL . "?page=login");
    exit;
}

$page = $_GET['page'] ?? 'home';

if (empty($_SESSION['logado']) && $page !== 'login' && $page !== 'api') {
    header("Location: " . BASE_URL . "?page=login");
    exit;
}

if ($page === 'api') {
    require_once __DIR__ . "/Config/backend.php";
    exit;
}

if ($page !== 'login') {
    include_once __DIR__ . "/App/views/layouts/header.php";
}

switch ($page) {

    case 'login':
        include_once __DIR__ . "/App/views/pages/login.php";
        break;

    case 'home':
        include_once __DIR__ . "/App/views/layouts/menu.html";
        break;

    case 'lubrification':
        include_once __DIR__ . "/App/views/pages/lubrification.php";
        break;

    case 'bip':
        include_once __DIR__ . "/App/views/pages/bip.php";
        break;

    case 'inventory':
        include_once __DIR__ . "/App/views/pages/inventory.php";
        break;

    case 'ttp':
        include_once __DIR__ . "/App/views/pages/ttp.php";
        break;

    case 'reports':
        include_once __DIR__ . "/App/views/pages/reports.php";
        break;

    default:
        include_once __DIR__ . "/App/views/layouts/menu.html";
        break;
}

if ($page !== 'login') {
    include_once __DIR__ . "/App/views/layouts/footer.html";
}

ob_end_flush();
