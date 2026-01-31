<?php
require_once __DIR__ . '/config.php';

define('APP_PATH', '/divb2/vulcanizacao/vulcflow/App/views');

if (isset($_GET['logoff'])) {
    logoutUser();
}

function valida_ldap($usr, $pwd)
{
    if (!function_exists('ldap_connect')) {
        die("Erro Crítico: Extensão LDAP não instalada.");
    }

    $ldap_server = "la.ad.goodyear.com";
    $auth_user   = "la\\" . $usr;
    
    $connect = @ldap_connect($ldap_server);
    if (!$connect) return false;

    ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

    $bind = @ldap_bind($connect, $auth_user, $pwd);
    ldap_close($connect);

    return $bind;
}

function loginUser($username)
{
    $db = new Database();
    $username = strtoupper(trim($username));
    $userData = getUserFullName($username, $db);
    
    if ($userData && !empty($userData->FNC_NOME)) {
        $fullName = trim($userData->FNC_NOME);
        $fullName = preg_replace('/\s+/', ' ', $fullName);
        
        $nameParts = explode(' ', $fullName);
        $nameParts = array_values(array_filter($nameParts));
        $totalParts = count($nameParts);

        if ($totalParts >= 2) {
            $first = ucfirst(strtolower($nameParts[0]));
            $last  = ucfirst(strtolower($nameParts[$totalParts - 1]));
            $displayName = $first . " " . $last;
        } else {
            $displayName = ucfirst(strtolower($nameParts[0]));
        }
    } else {
        $displayName = "Operador (" . $username . ")";
    }

    if (session_status() === PHP_SESSION_NONE) { session_start(); }

    $_SESSION['logado']      = true;
    $_SESSION['user']        = $username;
    $_SESSION['user_nome']   = $displayName; 
}

function logoutUser()
{
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    session_destroy();
    redirect('/login.php');
}

function checkLogin()
{
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_SESSION['logado'])) {
        redirect('/login.php');
    }
}

function doLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;

    $usuario = $_POST['usuario'] ?? '';
    $senha   = $_POST['senha'] ?? '';

    if (valida_ldap($usuario, $senha)) {
        loginUser($usuario);
        redirect('/home.php');
    }

    return "Usuário ou senha inválidos.";
}

function getUserFullName($username, $db)
{
    try {
        $usernameClean = str_replace("'", "", $username);
        $sql = "SELECT FNC_NOME FROM FAM_FUNCIONARIO@BR_FAM WHERE FNC_USERID = '$usernameClean'";
        $query = $db->query($sql);
        return $query ? $query->fetch(\PDO::FETCH_OBJ) : null;
    } catch (Exception $e) {
        return null;
    }
}

function redirect($page)
{
    header("Location: " . APP_PATH . $page);
    exit;
}