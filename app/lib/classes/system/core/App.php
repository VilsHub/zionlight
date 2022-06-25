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
    function __construct($loader=null, $router=null, $config){
        parent::__construct();
        global $env;  
        $this->env = $env;
        $this->loader = $loader;
        $this->config = $config;
        $this->router = $router;    
    }

    public function boot(){
        if($this->env == "web"){
            Session::start();
            CSRF::generateSessionToken($this->config->CSRFName);
        }
        loadEnv($this->config->envFile);
        setupEnvironment(env("ENVIRONMENT"), $this);
    }
    public function setPageTitle($value){
        $msg =  " Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("setPageTitle(x)", "black")." method argument must be a string";
        Validator::validateString($value, Message::write("error", $msg));
        echo "<script type = 'text/javascript'>document.querySelector('title').innerText = '{$value}' </script>";
    }

    public function databaseInitCheck(){
        $db_host	    = env("DB_HOST");
        $db_user	    = env("DB_USER");
        $db_db		    = env("DB_DATABASE");
        $db_pass	    = env("DB_PASSWORD");
        $db_charset	    = env("DB_CHARSET");
        $status         = false;
        global $env;

        try {
            //Build connection strings
            $opt    = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];

            $dsn    = "mysql:host={$db_host};dbname={$db_db};charset={$db_charset}";
            $pdo 	= new PDO($dsn, $db_user, $db_pass, $opt);
            $db 	= new DBAnt($pdo);            
        }catch (\Throwable $th) {

            
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
        $db_host	= env("DB_HOST");
        $db_user	= env("DB_USER");
        $db_pass	= env("DB_PASSWORD");
        $db_charset	= env("DB_CHARSET");
        global $env;

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

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
             //2002 => No connection to database, 1045 => invalid credentials
             if(isset($th->errorInfo[1]) || !$db_init){
                if(!$db_init){
                    $errorMessage = $this->getErrorMessage(1045);
                }else{
                    $errorNumber = $th->errorInfo[1];
                    if($errorNumber == 1045){//Invalid user credential
                        $errorMessage = $this->getErrorMessage(1045);
                    }else if($errorNumber == 2002){
                        $errorMessage = $this->getErrorMessage(2022);
                    }
                }
            }else{
                $errorMessage = $this->getErrorMessage(1);
            }
            
       
            try {
                $msg = $errorMessage["cli"];
                if($this->env != "cli") $msg = $errorMessage["web"];
                throw new Error($msg);
            }catch(\Throwable $th) {
                if($this->env != "cli"){
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

    private function getErrorMessage($errorNumber){
        $wMsg=""; $cMsg="";
        if($errorNumber == 1045){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>Invalid Database Credentials</span><br/>";
             $wMsg .= "<br/>Database in use = ".env("DB_DATABASE")."<br/>";
             $wMsg .= "<br/>Database User = ".env("DB_USER")."<br/>";
             $wMsg .= "Database Password  = ".env("DB_PASSWORD")."<br/>";
             $wMsg .= "<br/><span style='color:black;'>Please go the config file '<span style='font-weight: bold;'>{$this->config->envFile}</span>' and supply the database details for the current environment</span>";
             $wMsg .= "<br/><span style='color:black;'>Run the command when done: '<span style='font-weight: bold;'>php zlight initialize:database</span>'. This will guide you through a quick DB initialization</span>";

             //CLi message
             $cMsg .= "INVALID DATABASE CREDENTIALS\n\n";
             $cMsg .= "\tDatabase in use \t = ".env("DB_DATABASE")."\n";
             $cMsg .= "\tDatabase User \t\t = ".env("DB_USER")."\n";
             $cMsg .= "\tDatabase Password\t = ".env("DB_PASSWORD")."\n";
             $cMsg .= "\nPlease go the config file '".$this->color($this->config->envFile, "yellow","black").$this->color("' and supply the database details for the current environment\nRun the command when done: '", "light_red","black").$this->color("php zlight initialize:database", "yellow", "black").$this->color("'. This will guide you through a quick DB initialization", "light_red", "black");
        }else if($errorNumber == 1){
             //Web message
             $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE ENGINE NOT FOUND</span><br/>";
             $wMsg .= "<br/><span style='color:black;'>Please try installing a database engine, like MySQL";


             //CLi message
             $cMsg .= "DATABASE ENGINE NOT FOUND\n";
             $cMsg .= "Please try installing a database engine, like MySQL\n";
        }else if($errorNumber == 2002){
            //Web message
            $wMsg .= "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>Database connection error</span><br/>";
            $wMsg .= "<br/>Cannot connect to Database server<br/>";
            $wMsg .= "<br/><span style='color:black;'>Please try restarting the database server</span>";

            //CLi message
            $cMsg .= "DATABASE CONNECTION ERROR\n\n";
            $cMsg .= "Cannot connect to Database server\n";
            $cMsg .= "Please try restarting the database server";
        }  

        return [
            "web" => $wMsg,
            "cli" => $cMsg
        ];
    }
}
?>