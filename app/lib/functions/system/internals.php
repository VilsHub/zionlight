<?php
use vilshub\dbant\DBAnt;
use vilshub\validator\Validator;
use vilshub\Helpers\Style;


function loadEnv($fileSystem, &$app){

    $fileSystem->readContent("text", null, function($data) use ($app){
        $env = writeEnvValues($data);
        $app->config->appEnvs[$env["key"]] = $env["value"];
    });

}

function writeEnvValues($data){
    $config = explode("=", $data);
    $key    = trim($config[0]);
    unset($config[0]);
    $value  = trim(implode("=", $config));
   
    //Write values
    $_SERVER[$key]  = $value;
    $_ENV[$key]     = $value;
    if (strlen($key)){
        putenv("{$key}={$value}");
    }

    return [
        "key"   => $key,
        "value" => $value
    ];
}
function addEnv($key, $value){
    $_SERVER[$key]    = $value;
    $_ENV[$key]       = $value;
    putenv("{$key}={$value}");
}
function setupEnvironment($environment, &$app){
    
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

    if(getAppEnv("DB_APP") === "true"){//setup db connection
        $db_init	= false; 
        $db         = null;
        $xPDO       = null;
        $pdo        = null;

        if(getAppEnv("DB_SETUP_CHECK") === "true"){

            $serverStatus   = $app->pingDatabaseServer();
            if ($serverStatus["status"]){
                $db_init    = $serverStatus["db_init"];
                $dbStatus   = $app->databaseInitCheck();
            }
            
        }else{
            $db_host	= getAppEnv("DB_HOST");
            $db_db	    = getAppEnv("DB_DATABASE");
            $db_user	= getAppEnv("DB_USER");
            $db_pass	= getAppEnv("DB_PASSWORD");
            $db_charset	= getAppEnv("DB_CHARSET");
            $db_ssl     = getAppEnv("DB_SSL");
            $db_cert    = $app->config->miscFiles->db_certificate;;

            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            if ($db_ssl === "true") {
                $errorMessage   = $app->getErrorMessage("ze0001");
                $msg            = $errorMessage["cli"];
            
                if(PHP_SAPI != "cli") $msg = $errorMessage["web"];
            
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
                    if(PHP_SAPI != "cli"){
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
                    if(PHP_SAPI != "cli"){
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
        $app->db            = $app->config->db;
        
    }

}
function setupLogging($logFile){
    ini_set("error_log", $logFile);
}

function getAppEnv($key){
    
    global $app;

    if(array_key_exists($key, $_SERVER)){
        return $_SERVER[$key];
    }else if(array_key_exists($key, $_ENV)){
        return $_ENV[$key];
    }else if (array_key_exists($key, $app->config->appEnvs)){
        return $app->config->appEnvs[$key];
    }

}

function getProtectedPropertyValue(&$obj, $property){
    if ($obj != null){
        $reflection =  new ReflectionClass($obj);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }else{
        return null;
    }
    
}

function getErrorMessage(&$errorObj, $errorNumber=null){
    $errMessage     = getProtectedPropertyValue($errorObj, "message");
    $errFile        = getProtectedPropertyValue($errorObj, "file");
    $lineNo         = getProtectedPropertyValue($errorObj, "line");
    $errorNumber    = $errorNumber == null?getProtectedPropertyValue($errorObj, "code"):$errorNumber;

    $wMsg=""; $cMsg="";

    $msg = "The error '<span style='color:red;'>$errMessage</span>' occured at line: <span style='color:blue;'>".$lineNo. "</span> in the file <span style='color:blue;'>".$errFile."</span></br>";

    //Web message
    $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>ERROR ENCOUNTERED</span><br/>";
    $wMsg .= "<br/><span style='color:black;'>".$msg." </span><br/>";
    

    //CLi message
    $cMsg .= "ERROR ENCOUNTERED\n\n";
    $cMsg .= "\n".$msg."\n";
    

    return [
        "web" => $wMsg,
        "cli" => $cMsg
    ];
}
?>