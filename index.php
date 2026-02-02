<?php
require_once __DIR__ . '/Config/init.php'; 

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

if (isset($_GET['logoff'])) {
    session_destroy();
    header("Location: /vulcflow/login");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

/** * 3. BLOCO DE SEGURANÇA 
 * (Atualmente comentado para facilitar seus testes de layout)
 */
/*
if (empty($_SESSION['logado']) && $page !== 'login') {
    header("Location: /vulcflow/login");
    exit;
}
*/

if ($page !== 'login') {
    include_once "App/views/layouts/header.php";
}

switch ($page) {
    case 'login':
        include_once "App/views/pages/login.php";
        break;

    case 'home':
        include_once "App/views/layouts/menu.html";
        break;
    
    case 'lubrification':
        include_once "App/views/pages/lubrification.php";
        break;

    case 'bip':
        include_once "App/views/pages/bip.php";
        break;

    case 'inventory':
        include_once "App/views/pages/inventory.php";
        break;

    case 'ttp':
        include_once "App/views/pages/ttp.php";
        break;

    case 'reports':
        if (file_exists("reports.php")) {
            include_once "reports.php";
        } else {
            echo "Erro: Arquivo reports.php não encontrado na raiz.";
        }
        break;

    default:
        include_once "App/views/layouts/menu.html";
        break;
}

if ($page !== 'login') {
    include_once "App/views/layouts/footer.html";
}