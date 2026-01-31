<?php

	ini_set('session.gc_maxlifetime', 3600);
	
	session_set_cookie_params(3600);
	
	//set_time_limit(2800);
	
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	
	date_default_timezone_set('America/Sao_Paulo');
	
	/**
     * @ Váriaveis  globais
     */
    define('PATH_APP', dirname( __FILE__ ));
	
	/**
	 * 	Define Ambiente da Aplicação
	 */
	defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
	
	/**
	 * @ Conexão com Banco de Dados
	 * 
	 */
	define('HOSTNAME', (APPLICATION_ENV == 'production' ? '10.104.129.13' : '10.104.129.18') );
	define('DB_SERVICE', (APPLICATION_ENV == 'production' ? 'ORA' : 'ORAT') );
	define('DB_USER', 	  'INVENTARIO');
	define('DB_PASSWORD', 'R1ZBEOsDkPbFWGh');	
	define('DB_CHARSET',  'UTF8');
	define('DB_PORT', 	  1521);
	
	define('DEBUG', (APPLICATION_ENV == 'production' ? false : true));
	
	if ( ! defined('PATH_APP') ) exit;

    session_name('userdata_lms');
	
	session_start();
		
	
	if ( ! defined('DEBUG') || DEBUG === false ) {
		
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

	
?>
