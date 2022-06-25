<?php
use vilshub\dbant\DBAnt;

function loadEnv($envFile){
    $configs    = require_once($envFile);
    $_SERVER    = array_merge($_SERVER, $configs);
    $_ENV       = array_merge($_ENV, $configs);
}
function addEnv($key, $value){
    $_SERVER[$key]    = $value;
    $_ENV[$key]       = $value;
}
function env($key){
    return $_SERVER[$key];
}
function setupEnvironment($environment, &$app){
    global $env;  

    if(env("DB_APP")){//setup db connection
        $db_init	= false;
        if(env("DB_SETUP_CHECK")){
            $serverStatus   = $app->pingDatabaseServer();
            if ($serverStatus["status"]){
                $db_init    = $serverStatus["db_init"];
                $dbStatus   = $app->databaseInitCheck();
            }
        }else{
            $db_host	= env("DB_HOST");
            $db_db	    = env("DB_DATABASE");
            $db_user	= env("DB_USER");
            $db_pass	= env("DB_PASSWORD");
            $db_charset	= env("DB_CHARSET");

            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $xdsn   = "mysql:host={$db_host};charset={$db_charset}";	
            $xPDO   = new PDO($xdsn, $db_user, $db_pass, $opt);

            $dsn    = "mysql:host={$db_host};dbname={$db_db};charset={$db_charset}";
            $pdo 	= new PDO($dsn, $db_user, $db_pass, $opt);
            $db 	= new DBAnt($pdo);  
        }
    
        //connection   
        $app->config->pdo   = $db_init? $dbStatus["pdo"]:$pdo;   
        $app->config->xPDO  = $db_init? $serverStatus["xPDO"]:$xPDO;   
        $app->config->db    = $db_init? $dbStatus["db"]:$db;   
    }
   

    //Setup Error display
    switch ($environment)
	{
		case 'development':
			ini_set('display_errors', 1);
			break;
		case 'testing':
			error_reporting(-1);
			ini_set('display_errors', 1);
			break;
		case 'production':
			ini_set('display_errors', 0);
			if (version_compare(PHP_VERSION, '5.3', '>=')){
				error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
			}else{
				error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
			}
			break;
	}
}
?>