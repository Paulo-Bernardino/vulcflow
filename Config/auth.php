<?php
require_once __DIR__ . '/config.php';

// A URL base deve apontar para a pasta do projeto no servidor
define('BASE_URL', '/divb2/vulcanizacao/vulcflow/');

/**
 * Validação LDAP Goodyear
 */
function valida_ldap($usr, $pwd) {
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

/**
 * Cria a sessão e busca o nome no banco (FAM_FUNCIONARIO)
 */
function loginUser($username) {
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

    $_SESSION['logado']    = true;
    $_SESSION['user']      = $username;
    $_SESSION['user_nome'] = $displayName; 
}

/**
 * Mata a sessão e manda para a ROTA de login (não o arquivo .php)
 */
function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    session_destroy();
    
    // REDIRECIONAMENTO PARA A ROTA: Isso evita o loop infinito
    header("Location: " . BASE_URL . "?page=login");
    exit;
}

/**
 * Middleware para proteger as rotas internamente
 */
function checkLogin() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_SESSION['logado'])) {
        header("Location: " . BASE_URL . "?page=login");
        exit;
    }
}

/**
 * Processa o formulário de login (usado dentro da página login.php)
 */
function doLogin() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;

    $usuario = $_POST['usuario'] ?? '';
    $senha   = $_POST['senha'] ?? '';

    if (valida_ldap($usuario, $senha)) {
        loginUser($usuario);
        // SUCESSO: Redireciona para a ROTA 'home'
        header("Location: " . BASE_URL . "?page=home");
        exit;
    }

    return "Usuário ou senha inválidos.";
}

/**
 * Busca o nome completo via Link Goodyear
 */
function getUserFullName($username, $db) {
    try {
        $usernameClean = str_replace("'", "", $username);
        $sql = "SELECT FNC_NOME FROM FAM_FUNCIONARIO@BR_FAM WHERE FNC_USERID = '$usernameClean'";
        $query = $db->query($sql);
        return $query ? $query->fetch(\PDO::FETCH_OBJ) : null;
    } catch (Exception $e) {
        return null;
    }
}