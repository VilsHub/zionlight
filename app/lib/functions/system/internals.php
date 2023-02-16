<?php
use vilshub\dbant\DBAnt;
use vilshub\validator\Validator;
use vilshub\Helpers\Style;

function loadEnv($envFile){
    $fileSystem = new FileSystem($envFile);
    $fileSystem->readContent("text", null, function($data){
        writeEnvValues($data);
    });
}

function writeEnvValues($data){
    $config = explode("=", $data);
    $key    = $config[0];
    unset($config[0]);
    $value  = trim(implode("=", $config));
   
    //Write values
    $_SERVER[$key]  = $value;
    $_ENV[$key]     = $value;
    putenv("{$key}={$value}");
}
function addEnv($key, $value){
    $_SERVER[$key]    = $value;
    $_ENV[$key]       = $value;
    putenv("{$key}={$value}");
}
function setupEnvironment($environment, &$app){

    if(getenv("DB_APP")){//setup db connection
        $db_init	= false; 
        $db         = null;
        $xPDO       = null;
        $pdo        = null;

        if(getenv("DB_SETUP_CHECK")){
            $serverStatus   = $app->pingDatabaseServer();
            if ($serverStatus["status"]){
                $db_init    = $serverStatus["db_init"];
                $dbStatus   = $app->databaseInitCheck();
            }
        }else{
            $db_host	= getenv("DB_HOST");
            $db_db	    = getenv("DB_DATABASE");
            $db_user	= getenv("DB_USER");
            $db_pass	= getenv("DB_PASSWORD");
            $db_charset	= getenv("DB_CHARSET");
            $db_ssl     = getenv("DB_SSL");
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