<?php

if (session_status() === PHP_SESSION_NONE) {

    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // 1 se HTTPS

    session_set_cookie_params([
        'lifetime' => 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_name('VULCFLOWSESSID');
    session_start();
}

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

define('PATH_APP', dirname(__FILE__));

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('HOSTNAME', (APPLICATION_ENV == 'production' ? '10.104.129.13' : '10.104.129.18'));
define('DB_SERVICE', (APPLICATION_ENV == 'production' ? 'ORA' : 'ORAT'));
define('DB_USER', 'INVENTARIO');
define('DB_PASSWORD', 'R1ZBEOsDkPbFWGh');
define('DB_CHARSET', 'UTF8');
define('DB_PORT', 1521);

define('DEBUG', (APPLICATION_ENV == 'production' ? false : true));

if (!defined('DEBUG') || DEBUG === false) {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

require_once 'global-functions.php';
require_once 'Db/Database.php';
require_once 'support.php';
