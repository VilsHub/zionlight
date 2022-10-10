<?php
use vilshub\dbant\DBAnt;
use vilshub\validator\Validator;

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

    if(env("DB_APP")){//setup db connection
        $db_init	= false; 
        $db         = null;
        $xPDO       = null;
        $pdo        = null;

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
            $db_ssl     = env("DB_SSL");
            $db_cert    = $app->config->miscFiles->db_certificate;;

            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            if ($db_ssl) {
                $errorMessage   = $app->getErrorMessage("ze0001");
                $msg            = $errorMessage["cli"];
            
                if($app->env != "cli") $msg = $errorMessage["web"];
            
                Validator::validateFile($db_cert, $msg);
                $opt[PDO::MYSQL_ATTR_SSL_CA] =  $db_cert;
            }

            try {
                $xdsn   = "mysql:host={$db_host};charset={$db_charset}";	
                $xPDO   = new PDO($xdsn, $db_user, $db_pass, $opt);
    
                $dsn    = "mysql:host={$db_host};dbname={$db_db};charset={$db_charset}";
                $pdo 	= new PDO($dsn, $db_user, $db_pass, $opt);
                $db 	= new DBAnt($pdo); 
            } catch(\Throwable $th){
                $type = (is_array($th->errorInfo)) ? "array":"string";
                
                if ($type == "array"){
                    $errorNumber = $th->errorInfo[1];    
                    $errorMessage = $app->getErrorMessage($errorNumber);
                    if($app->env != "cli"){
                        ErrorHandler::throwError($errorNumber, $errorMessage["web"], null, null, null);
                    }else{
                        echo "\n";
                        $app->write($errorMessage["cli"], "light_red", "black");
                        echo "\n";
                        die();
                    }
                    die();
                }else{
                    $errorMessage = $app->getErrorMessage(1);
                    if($app->env != "cli"){
                        ErrorHandler::throwError(null, $errorMessage["web"], null, null, null);
                    }else{
                        echo "\n";
                        $app->write($errorMessage["cli"], "light_red", "black");
                        echo "\n";
                        die();
                    }
                    die();
                }  
            }           
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
			include($app->config->miscFiles->phpini_development);
			break;
		case 'testing':
			include($app->config->miscFiles->phpini_testing);
			break;
		case 'production':
			include($app->config->miscFiles->phpini_production);
			break;
	}
}
function setupLogging($logFile){
    ini_set("error_log", $logFile);
}
?>