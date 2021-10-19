<?php
require_once(dirname(__DIR__)."/helpers/CLIColors.php");

class Boot extends CLIColors{
    function __construct($dbInfo){
        parent::__construct();
        global $env;
        $this->env      = $env;
        $this->dbInfo   = $dbInfo;
        try {
            $xdsn = "mysql:host={$dbInfo["host"]}";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];
            $this->xPdo = new PDO($xdsn, $dbInfo["user"], $dbInfo["pass"], $opt);
            if(!$this->databaseExist($dbInfo["db"])){
                require (dirname(__DIR__, 5)."/.env");
                //init status

                if($databaseInit){
                    //reset database init
                    $this->resetDatabaseInit();
                    header("location: {$_REQUEST["uri"]}");
                }
                
            }
        }catch (\Throwable $th) {
            //2002 => No connection to database, 1045 => invalid credentials
            if(isset($th->errorInfo[1])){
                $errorNumber = $th->errorInfo[1];
                if($errorNumber == 1045){//Invalid user credential
                    //Web message
                    $wMsg = "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>Invalid Database Credentials</span><br/>";
                    $wMsg .= "<br/>Database User = {$dbInfo["user"]}<br/>";
                    $wMsg .= "Database Password  = {$dbInfo["pass"]}<br/>";
                    $wMsg .= "<br/><span style='color:black;'>Please go the config file '<span style='font-weight: bold;'>config/dbDetails.php</span>' and supply the database details for the current environment</span>";
                    $wMsg .= "<br/><span style='color:black;'>Run the command when done: '<span style='font-weight: bold;'>php zlight initialize:database</span>'. This will guide you through a quick DB initialization</span>";

                    //CLi message
                    $cMsg = "INVALID DATABASE CREDENTIALS\n\n";
                    $cMsg .= "\tDatabase User \t\t = {$dbInfo["user"]}\n";
                    $cMsg .= "\tDatabase Password\t = {$dbInfo["pass"]}\n";
                    $cMsg .= "\nPlease go the config file '".$this->color("config/dbDetails.php", "yellow","black").$this->color("' and supply the database details for the current environment\nRun the command when done: '", "light_red","black").$this->color("php zlight initialize:database", "yellow", "black").$this->color("'. This will guide you through a quick DB initialization", "light_red", "black");
                }else if($errorNumber == 2002){
                    //Web message
                    $wMsg = "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>Database connection error</span><br/>";
                    $wMsg .= "<br/>Cannot connect to Database server<br/>";
                    $wMsg .= "<br/><span style='color:black;'>Please try restarting the database server</span>";

                    //CLi message
                    $cMsg = "DATABASE CONNECTION ERROR\n\n";
                    $cMsg .= "Cannot connect to Database server\n";
                    $cMsg .= "Please try restarting the database server";
                }
            }else{
                $errorNumber = 1;
                 //Web message
                 $wMsg = "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>DATABASE ENGINE NOT FOUND</span><br/>";
                 $wMsg .= "<br/><span style='color:black;'>Please try installing a database engine, like MySQL";


                 //CLi message
                 $cMsg = "DATABASE ENGINE NOT FOUND\n";
                 $cMsg .= "Please try installing a database engine, like MySQL\n";
            }
            
       
            try {
                $msg = $cMsg;
                if($env != "cli") $msg = $wMsg;
                throw new Error($msg);
            }catch(\Throwable $th) {
                if($env != "cli"){
                    trigger_error($th);
                }else{
                    echo "\n";
                    $this->write($cMsg, "light_red", "black");
                    echo "\n";
                    die();
                }
                die();
            }
        }
    }
    public function databaseInitCheck(){
        if($this->env != "cli"){//web
            if(!Session::exist("dCheck")){ //To check for database setup only once
                //check for database initialization
                if(!$this->databaseExist($this->dbInfo["db"])){
                    $this->executeCheck();
                }else{
                    Session::set("dCheck", true);
                } 
            }
        }else{//cli
            if(!$this->databaseExist($this->dbInfo["db"])){
                $this->executeCheck();
            }
        }
    }
    private function executeCheck(){
        if(!$this->databaseExist($this->dbInfo["db"])){
            $wMsg = "<br/><span style='color:#93381a;text-transform: uppercase;font-weight: bold;'>Application setup failure</span><br/>";
            $wMsg .= "<br/>No database initialization has been done.<br/>";
            $wMsg .= "<br/><span style='color:black;'>Run the command to initial database: '<span style='font-weight: bold;'>php zlight initialize:database</span>'. This will guide you through a quick DB initialization</span>";
            try {
                throw new Error($wMsg);
            } catch (\Throwable $th) {
                trigger_error($wMsg);
            }
        }
    }
    private function databaseExist($name){
        $sql = "SHOW DATABASES LIKE '{$name}'";
        $run = $this->xPdo->query($sql);
        $data = $run->fetchAll();
        return count($data) > 0;
    }
    private function getEnvFile($dbState){
        $contents = "";
        $file = dirname(__DIR__, 5)."/.env";
        $fileHandler = fopen($file, "r");
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if(strpos($line, 'databaseInit') != false){
                $search = $dbState == "true"?"false":"true";
                $contents .= str_replace($search, $dbState, $line);
            }else{
                $contents .= $line;
            }
        }
        fclose($fileHandler);
        return $contents;
    }
    private function executeWrite($fileName, $content){
        $fileHandler = fopen($fileName, "w");
        if (fwrite($fileHandler, $content) > 0){
            return true;
        }else{
            return false;
        };
    }
    private function resetDatabaseInit(){
        $file = dirname(__DIR__, 5)."/.env";
        $updateEnvFileContent = $this->getEnvFile("false");
        return $this->executeWrite($file, $updateEnvFileContent);
    }
}
?>