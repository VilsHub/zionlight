<?php
use vilshub\router\Router;
use vilshub\helpers\Message;
use vilshub\helpers\Get;
use vilshub\helpers\Style;
use vilshub\helpers\textProcessor;
use vilshub\validator\Validator;
use vilshub\dbant\DBAnt;

require_once(dirname(__DIR__)."/helpers/CLIColors.php");

class App extends CLIColors{
    public $loader, $config, $router, $db;

    function __construct($loader, $router, $config){
        parent::__construct();
        $this->loader = $loader;
        $this->config = $config;
        $this->router = $router;    
    }

    public function boot($fileSystem){
        if(PHP_SAPI != "web"){
            Session::start();
            CSRF::generateSessionToken($this->config->CSRFName);
        }

        setupLogging($this->config->logFile);
        loadEnv($fileSystem, $this);
        setupEnvironment(getAppEnv("ENVIRONMENT"), $this);
        
    }

    public function DBInit($target){
        setupDBConnection($this, $target);
    }

    public function setPageTitle($value){
        $msg =  " Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("setPageTitle(x)", "black")." method argument must be a string";
        Validator::validateString($value, Message::write("error", $msg));
        echo "<script type = 'text/javascript'>document.querySelector('title').innerText = '{$value}' </script>";
    }

    public function databaseInitCheck(){
        $db_host	    = getAppEnv("DB_HOST");
        $db_user	    = getAppEnv("DB_USER");
        $db_db		    = getAppEnv("DB_DATABASE");
        $db_pass	    = getAppEnv("DB_PASSWORD");
        $db_charset	    = getAppEnv("DB_CHARSET");
        $status         = false;
        $db_ssl         = getAppEnv("DB_SSL");
        $db_cert        = $this->config->miscFiles->db_certificate;

        try {
            //Build connection strings
            $opt    = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            

            if ($db_ssl === "true") {
                $errorMessage   = $this->getErrorMessage("ze0001");
                $msg            = $errorMessage["cli"];
                
                if(PHP_SAPI != "cli") $msg = $errorMessage["web"];
                
                Validator::validateFile($db_cert, $msg);
                $opt[PDO::MYSQL_ATTR_SSL_CA] =  $db_cert;
            }

            $dsn    = "mysql:host={$db_host};dbname={$db_db};charset={$db_charset}";
            $pdo 	= new PDO($dsn, $db_user, $db_pass, $opt);
            
            $db 	= new DBAnt($pdo);   

        }catch (\Throwable $th) {
            
            $errorMessage = $this->getErrorMessage($th);
           
            try {
                $msg = $errorMessage["cli"];
                if(PHP_SAPI != "cli") $msg = $errorMessage["web"];
                throw new Error($msg);
            }catch(\Throwable $th) {
                if(PHP_SAPI != "cli"){
                    trigger_error($th);
                }else{
                    echo "\n";
                    $this->write($errorMessage["cli"], "light_red", "black");
                    echo "\n";
                    die();
                }
                die();
            }
        }

        return [
            "status"    => $status,
            "db"        => $db,
            "pdo"       => $pdo
        ];
    }
    
    public function databaseExist($name){
        $sql = "SHOW DATABASES LIKE '{$name}'";
        $run = $this->pdo->query($sql);
        $data = $run->fetchAll();
        return count($data) > 0;
    }

    public function pingDatabaseServer(){
        $status     = false;
        $db_init    = false;
        $db_host	= getAppEnv("DB_HOST");
        $db_user	= getAppEnv("DB_USER");
        $db_pass	= getAppEnv("DB_PASSWORD");
        $db_charset	= getAppEnv("DB_CHARSET");
        $db_ssl     = getAppEnv("DB_SSL");
        $db_cert    = $this->config->miscFiles->db_certificate;

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

        if ($db_ssl == "true") {
            $errorMessage   = $this->getErrorMessage("ze0001");
            $msg            = $errorMessage["cli"];
            
            if(PHP_SAPI != "cli") $msg = $errorMessage["web"];
            
            Validator::validateFile($db_cert, $msg);
            $opt[PDO::MYSQL_ATTR_SSL_CA] =  $db_cert; 
        } 

        try{
            $xdsn       = "mysql:host={$db_host};charset={$db_charset}";	
            $pdo        = new PDO($xdsn, $db_user, $db_pass, $opt);

            $sql        = "SHOW DATABASES";
            $run        = $pdo->query($sql);
            $status     = $run->rowCount() > 0;

            if ($status){
                $status     = true;
                $db_init    = true;
            }

        }catch(\Throwable $th){   
            $errCode = getProtectedPropertyValue($errorObj, "code");

            if($errCode != 1){
                $errorMessage = $this->getErrorMessage($th);
            }else{
                $errorMessage = $this->getErrorMessage($th, 1);
            }
        
            try {
                $msg = $errorMessage["cli"];
                if(PHP_SAPI != "cli") $msg = $errorMessage["web"];
                throw new Error($msg);
            }catch(\Throwable $th) {
                if(PHP_SAPI != "cli"){
                    trigger_error($th);
                }else{
                    echo "\n";
                    $this->write($errorMessage["cli"], "light_red", "black");
                    echo "\n";
                    die();
                }
                die();
            }
        };

        return [
            "status"    => $status,
            "db_init"   => $db_init,
            "xPDO"      => $pdo
        ];
    }

    public function getErrorMessage(&$errorObj, $errorNumber=null){

        $errMessage     = getProtectedPropertyValue($errorObj, "message");
        $errFile        = getProtectedPropertyValue($errorObj, "file");
        $lineNo         = getProtectedPropertyValue($errorObj, "line");
        $errorNumber    = $errorNumber == null?getProtectedPropertyValue($errorObj, "code"):$errorNumber;

        $wMsg=""; $cMsg="";

        if($errorNumber == 1045){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>INVALID DATABASE CREDENTIALS</span><br/>";
             $wMsg .= "<br/>Database in use = ".getAppEnv("DB_DATABASE")."<br/>";
             $wMsg .= "<br/>Database User = ".getAppEnv("DB_USER")."<br/>";
             $wMsg .= "Database Password  = ".getAppEnv("DB_PASSWORD")."<br/>";
             $wMsg .= "<br/><span style='color:black;'>Please go the config file '<span style='font-weight: bold;'>{$this->config->envFile}</span>' and supply the database details for the current environment</span>";
             $wMsg .= "<br/><span style='color:black;'>Run the command when done: '<span style='font-weight: bold;'>php zlight initialize:db</span>'. This will guide you through a quick DB initialization process</span>";

             //CLi message
             $cMsg .= "INVALID DATABASE CREDENTIALS\n\n";
             $cMsg .= "\tDatabase in use \t = ".getAppEnv("DB_DATABASE")."\n";
             $cMsg .= "\tDatabase User \t\t = ".getAppEnv("DB_USER")."\n";
             $cMsg .= "\tDatabase Password\t = ".getAppEnv("DB_PASSWORD")."\n";
             $cMsg .= "\nPlease go the config file '".$this->color($this->config->envFile, "yellow","black").$this->color("' and supply the database details for the current environment\nRun the command when done: '", "light_red","black").$this->color("php zlight initialize:db", "yellow", "black").$this->color("'. This will guide you through a quick DB initialization process", "light_red", "black");
        }else if($errorNumber == 1){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE ENGINE NOT FOUND</span><br/>";
             $wMsg .= "<br/><span style='color:black;'>Please try installing a database engine, like MySQL";


             //CLi message
             $cMsg .= "DATABASE ENGINE NOT FOUND\n";
             $cMsg .= "Please try installing a database engine, like MySQL\n";
        }else if($errorNumber == 2002){
            //Web message
            $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE CONNECTION ERROR</span><br/>";
            $wMsg .= "<br/>A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond. <br/>";
            $wMsg .= "<br/><span style='color:black;'>Please try restarting the database server or check firewall</span>";

            //CLi message
            $cMsg .= "DATABASE CONNECTION ERROR\n\n";
            $cMsg .= "A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond. \n";
            $cMsg .= "Please try restarting the database server or check firewall";
        }else if($errorNumber == 1049){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE NOT FOUND</span><br/>";
             $wMsg .= "<br/>Cannot connect to the configured database '".getAppEnv("DB_DATABASE")."'<br/>";
             $wMsg .= "<br/><span style='color:black;'>Please go the config file '<span style='font-weight: bold;'>{$this->config->envFile}</span>' and supply the database details for the current environment";
             $wMsg .= "<br/><span style='color:black;'>You can also run the command ''<span style='font-weight: bold;'>php zlight create:db</span>' This will create a new database";
             $wMsg .= "<br/><span style='color:black;'>Run the command when done: '<span style='font-weight: bold;'>php zlight initialize:db</span>'. This will guide you through a quick DB initialization process</span>";
 
             //CLi message
             $cMsg .= "DATABASE NOT FOUND ERROR\n\n";
             $cMsg .= "Cannot connect to the configured database '".getAppEnv("DB_DATABASE")."'\n";
             $cMsg .= "\nYou can also run the command '".$this->color("php zlight create:db", "yellow","black").$this->color("'. This will create a new database", "light_red", "black");
             $cMsg .= "\n".$this->color("Please go the config file '", "light_red","black").$this->color($this->config->envFile, "yellow","black").$this->color("' and supply the database details for the current environment\nRun the command when done: '", "light_red","black").$this->color("php zlight initialize:db", "yellow", "black").$this->color("'. This will guide you through a quick DB initialization process", "light_red", "black");
        }else if($errorNumber == 3159){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>CCANNOT CONNECT TO DATABASE SERVER</span><br/>";
             $wMsg .= "<br/>Connections using insecure transport are prohibited while --require_secure_transport=ON <br/>";
             $wMsg .= "<br/><span style='color:black;'>Setup SSL for database connection or turn OFF SSL on the database server</span>";
             
 
             //CLi message
             $cMsg .= "CANNOT CONNECT TO DATABASE SERVER\n\n";
             $cMsg .= "Connections using insecure transport are prohibited while --require_secure_transport=ON\n";
             $cMsg .= "\nSetup SSL, for database connection or turn OFF SSL on the database server";
        }else if($errorNumber == "ze0001"){
            // ze0001 cannot find the specified certificat file
            //Web message
            $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE SERVER SSL ISSUE</span><br/>";
            $wMsg .= "<br/><span style='color:black;'>The specified SSL file: '<span style='font-weight: bold;'>{$this->config->miscFiles->db_certificate}</span>' is not found</span>";
            

            //CLi message
            $cMsg .= "DATABASE SERVER SSL ISSUE\n\n";
            $cMsg .= "\nThe specified SSL file: '".$this->color($this->config->miscFiles->db_certificate, "yellow","black").$this->color("' is not found'", "light_red","black");

        } else { //Error object must be supplied

            $msg = "The error '<span style='color:red;'>$errMessage</span>' occured at line: <span style='color:blue;'>".$lineNo. "</span> in the file <span style='color:blue;'>".$errFile."</span></br>";

            //Web message
            $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>ERROR ENCOUNTERED</span><br/>";
            $wMsg .= "<br/><span style='color:black;'>".$msg." </span><br/>";
            

            //CLi message
            $cMsg .= "ERROR ENCOUNTERED\n\n";
            $cMsg .= "\n".$msg."\n";
        } 

        return [
            "web" => $wMsg,
            "cli" => $cMsg
        ];
    }

    public function getLoadBase($displayBlockName){
        return $this->config->displayDir."/".$displayBlockName."/".$this->config->contentsFolder->load;
    }

    public function getDisplayBlock($block, $displayBlockFile){
        return $this->config->displayDir."/".$block."/".$displayBlockFile;
    }

    public function getFragment($block, $fragmentFile){
        global $app;
        extract(["app" => $app]);
        require_once($this->config->displayDir."/".$block."/".$this->config->fragmentsDir."/".$fragmentFile);
    }

    public function getDisplayFile($block, $targetDisplayFile){
        /**
         *  @ targetDisplayFile: specify file relative to the display load directory. Example /root/home.php
         */
        return $this->config->displayDir."/".$block."/".$this->config->contentsFolder->load.$targetDisplayFile;
    }

    public function generateError($errorMessage, $interface): void{
        /**
         * @param string $interface: string of either 'cli' or 'web  
         */
     
        $parsedErrorMessage = $this->getErrorMessage($errorMessage);
        trigger_error($parsedErrorMessage[$interface]);
    } 
}

?>