<?php
trait Commands {
    //Commands
    public function startServer(){

        global $argv;

        //get port
        $serverCommand = $this->getAction($argv[1]);

        if(array_key_exists("object", $serverCommand)){
            $port = explode("@", $serverCommand["object"]);
            $total = count($port);
            if($total < 2 || $total > 2 ){
                $this->error(["Specify server port using : " ,"@portNumber"," example: shine:@3000"]);
            }

            //validate port number
            if(!is_int((int) $port[1])){
                $this->error(["The specified port : " ,$port[1]," is invalid, must be a postive initeger"]);
            }

            if((int)  $port[1] < 1){
                $this->error(["The specified port : " ,$port[1]," is invalid, must be greater than 0"]);
            }
            
        }else{
            $this->error(["Specify server port using : " ,"@portNumber ","example: shine:@3000"]);
        }
        

        $command = "php -S localhost:".$port[1]." -t public/";
        $output = shell_exec("php -v");  //Check if php path exist     

        if ($output != ""){
            $this->write("ZLight App ".$this->configs->appName." started at 127.0.0.1:".$port[1]."\n", "green", "black");
            $this->write($this->color("Press ", "blue", "black").$this->color("Ctrl + C", "yellow", "black"). $this->color(" to shutdown server\n", "blue", "black"), "green", "black");
            shell_exec($command);
        }
    }
    public function build($object){

        global $argv;

        //Action name must only be alphabets
        
        switch (strtolower($object)) {
            case 'schema':
                //track setup
                $this->setupCheck();
                if(!isset($argv[2])){// Please specify the build type
                    $this->error(["Please specify the build type: '-new | -n', '-tracked | -t' or  'schemaFileName'","",""]);
                }
                
                //build specific schema, init or new
                $arg2 = strtolower($argv[2]);
                if($arg2 == "-tracked" || $arg2 == "-t"){//build only tracked
                    $mode = "tracked";
                }else if($arg2 == "-new" || $arg2 == "-n"){//build new schema
                    $mode = "new";
                }else{//build specific schema
                    $mode = "file";
                }

                if($mode == "file"){
                    //try build
                    
                    //validate name
                    if(!$this->validate("alphaNum", $argv[2])){
                        $this->error(["Schema name must be alpha numeric only, the name:", $argv[2], " is not all alpha numeric"]);
                    }
                    
                    $buildSchema = $this->buildSchema($argv[2]);

                    if($buildSchema["status"]){//Schema exist
                        if($buildSchema["code"] == 1){//Built successfully
                            $this->success(["The schema: '{$argv[2]}' has been built successfully", "",""], false);
                        }
                    }else{//No build
                        if($buildSchema["code"] == 4){
                            $this->warning(["The schema file: '{$argv[2]}.sql' is empty, found nothing to build", "",""]);
                        }else if($buildSchema["code"] == 2){
                            $this->error(["The schema file : '{$argv[2]}' is not found. Create one using the: ","create:schema"," command"]);
                        }else if($buildSchema["code"] == 3){//Already built
                            $this->warning(["The schema: '{$argv[2]}' has been built already. Use the command: ", "reset:schema"," to reset the schema if you want to build again"]);
                        }
                    }
                }else{
                    $this->autoBuildSchema($mode);
                }                    
                
                break;
        }
    }
    public function reset($object){

        global $argv;

        switch (strtolower($object)) {
            case 'schema':  
                //track setup
                $this->setupCheck();

                $rebuild=false; 
                $schemaFile = "";
                $auto=true;
                if(isset($argv[2])){
                    
                    if(strtolower($argv[2]) != "-r"){// target file given to be reset but not to build
                        //Validate schema name
            
                        if(!$this->validate("alphaNum", $argv[2])){
                            $this->error(["Schema name must be alpha numeric only, the name:", $argv[2], " is not all alpha numeric"]);
                        }

                        $schemaFile = $argv[2];
                        $auto = false;
                        $rebuild = false;
                    }else if(strtolower($argv[2]) == "-r"){//auto rebuilding enable
                        if(isset($argv[3])){ //has specific schema for reseting and building
                            if(!$this->validate("alphaNum", $argv[3])){
                                $this->error(["Schema name must be alpha numeric only, the name:", $argv[3], " is not all alpha numeric"]);
                            }
                            $auto = false;
                            $schemaFile = $argv[3];
                        }
                        $rebuild = true;
                    }    
                }   

                if(!$auto){ //Reset specific schema
                    $schemaFileData = $this->getSchema($schemaFile);
                    if($schemaFileData["status"]){//schema exist
                        if(!$this->resetStatus($schemaFile)){// has not been reset
                            $answered = false;
                            while(!$answered){
                                $dLabel = $rebuild?" and rebuild again":"";
                                $prompt = $this->color(" Are you sure you want to drop the schema: '{$schemaFile}' {$dLabel}? Y or N ", "blue", "yellow");
                                $input =  $this->readLine($prompt);
                                
                                if($input != "n" && $input != "y"){
                                    echo "Please press 'Y' for yes and 'N' for no\n";
                                    continue;
                                } 

                                if(strtolower($input) == "y"){
                                    //try reset
                                    $resetSchema = $this->resetSchema($schemaFile, $schemaFileData["tableName"], $rebuild);
                                    if($resetSchema["status"]){//Schema exist
                                        if($resetSchema["code"] == "r2"){
                                            $this->success(["The schema: '{$schemaFile}' has been reset and rebuilt successfully", "",""], false);
                                        }else if($resetSchema["code"] == "r1"){
                                            $this->success(["The schema: '{$schemaFile}' has been reset successfully without rebuilding","",""]);
                                        }
                                    }else{
                                        if($resetSchema["code"] == "r4"){
                                            $this->warning(["The schema: '{$schemaFile}' was not tracked, and cannot be reset","",""]);
                                        }
                                    }
                                    $answered = true;
                                }else{
                                    $answered = true;
                                }
                            }
                            
                        }else{// has been reset
                            $this->warning(["The schema : '{$schemaFile}' has already been reset ","",""]);
                        }
                    }else{//Schema does not exist
                        $this->error(["The schema file: '{$schemaFile}.sql' does not exist. Create is using the: ","create:schema ","command"]);
                    }
                }else{// run auto reset
                    $this->autoResetSchema($rebuild, $rebuild);
                }
                break;
        }
    }
    public function untrack($object){

        global $argv;

        switch (strtolower($object)) {
            case 'schema': 
                //track setup
                $this->setupCheck();

                if(!isset($argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be untracked", "", ""]);
                } 

                if(!$this->validate("alphaNum", $argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $argv[2], " is not all alpha numeric"]);
                }

                $fileCheck = $this->getSchema($argv[2]);
                
                $answered = false;
                while(!$answered){
                    if($fileCheck["status"]){ //file exist
                        $prompt = $this->color(" The schema file: '{$argv[2]}.sql' exist, are you sure you want to untrack? Y or N ", "blue", "yellow");
                    }else{
                        $prompt = $this->color(" Are you sure you want to untrack the schema: '{$argv[2]}'? Y or N ", "blue", "yellow");
                    }
                    
                    $input =  $this->readLine($prompt);  
                    if($input != "n" && $input != "y"){
                        echo "Please press 'Y' for yes and 'N' for no\n";
                        continue;
                    } 

                    if(strtolower($input) == "y"){
                        $untrackSchema = $this->untrackSchema($argv[2]);
                        if($untrackSchema["status"] && $untrackSchema["rowCount"] > 0){//
                            $this->success(["The schema: '{$argv[2]}' has been untracked successfully", "",""], false);
                        }else{
                            $this->warning(["The schema: '{$argv[2]}' was never tracked", "",""]);
                        }
                        $answered = true;
                    }else{
                        $answered = true;
                    }
                }
                break;
        }     
    }
    public function track($object){

        global $argv;

        switch (strtolower($object)) {
            case 'schema': 
                //track setup
                $this->setupCheck();

                if(!isset($argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be tracked", "", ""]);
                } 

                if(!$this->validate("alphaNum", $argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $argv[2], " is not all alpha numeric"]);
                }
                
                $checkSchema = $this->getSchema($argv[2]);
                
                if($checkSchema["status"]){//schema exist, check if tracked before tracking
                    $isTracked = $this->trackStatus($argv[2], $checkSchema["tableName"]);
                    if(!$isTracked){
                        $trackSchema = $this->trackSchema($argv[2], $checkSchema["tableName"]);
                        if($trackSchema["status"] && $trackSchema["rowCount"] > 0){//tracked successfuly
                            $this->success(["The schema: '{$argv[2]}' has been tracked successfully. Run the command: ", "build:schema {$argv[2]}"," to build the schema"], false);
                        }else{
                            $this->error(["Error tracking the schema: '{$argv[2]}'", "",""], false);
                        }
                    }else{
                        $this->warning(["The schema: '{$argv[2]}' is tracked already", "",""], false);
                    }                    
                }else{//file not exist
                    $this->error(["The schema file: '{$argv[2]}.sql' does not exist", "",""], false);
                }
                break;
        }
    }
    public function delete($object){

        global $argv;

        switch (strtolower($object)) {
            case 'schema':
                //track setup
                $this->setupCheck();

                if(!isset($argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be deleted", "", ""]);
                } 

                if(!$this->validate("alphaNum", $argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $argv[2], " is not all alpha numeric"]);
                }

                $checkSchema = $this->getSchema($argv[2]);
                
                if($checkSchema["status"]){//schema exist, delete
                    $answered = false;
                    while(!$answered){
                        $prompt = $this->color(" The schema file: '{$argv[2]}.sql' exist, are you sure you want to delete? Y or N ", "blue", "yellow");
                        $input =  $this->readLine($prompt);  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        if(strtolower($input) == "y"){
                            if($this->deleteSchema($argv[2], $checkSchema["tableName"])){
                                $this->success(["The schema: '{$argv[2]}' has been deleted successfully", "",""], false); 
                                $answered = true;
                            }                        
                        }else{
                            $answered = true;
                        }
                    }
                }else{
                    $this->error(["The schema file: '{$argv[2]}.sql' does not exist ", "", ""]);
                }
            break;
        }
    }
    public function create($object){

        global $argv;

        //check for new object name
        if(!isset($argv[2])){
            $this->error(["Please specify the ", $object, " name"]);
        }

        if($object != "db" && $object !=  "display"){
            //name must only be alphabets
            if(!$this->validate("alpha", $argv[2])){
                $label = strtolower($object) == "schema"?"schema":$argv[2];
                $this->error(["The ".ucwords($label)." name must be alphabets only, the name: ", $argv[2], " is not all alphabets"]);
            }
        }
    
        switch (strtolower($object)) {
            case 'controller':
                $this->buildTemplate("controller");
                break;
            case 'model':
                $this->buildTemplate("model");
                break;
            case 'middleware':
                $this->buildTemplate("middleware");
                break;
            case 'queriesbank':
                $this->buildTemplate("queriesbank");
                break;
            case 'service':
                $this->buildTemplate("service");
                break;
            case 'schema':
                // php zlight create:schema schemaName [DDSType] tableName
                if ($this->dbInfo["isDBApp"]){
                    $this->buildTemplate("schema");
                }else{
                    $this->warning(["'DB_APP' config is not set to: ", "true", " in the ".$this->configs->envFile]);
                }
                
                break;
            case 'db':
                if ($this->dbInfo["isDBApp"]){
                    $this->createDatabase();
                }else{
                    $this->warning(["'DB_APP' config is not set to: ", "true", " in the ".$this->configs->envFile]);
                }
                
                break;
            case 'trait':
                $this->buildTemplate("trait");
                break;
            case 'display':
                $this->buildTemplate("display");
                break;
            case 'seeder':
                $this->buildTemplate("seeder");
                break;
            default:
                # code...
                break;
        }
    }
    public function initialize($object){              
        switch (strtolower($object)) {
            case 'db': 

                $parsedDbInfo     = [
                    "pass"      =>[
                        "old"   => $this->dbInfo["pass"],
                        "new"   => null
                    ],
                    "user"      =>[
                        "old"   => $this->dbInfo["user"],
                        "new"   => null,
                    ],
                    "charset"   =>[
                        "old"   => $this->dbInfo["charset"],
                        "new"   => null,
                    ],
                    "database"   =>[
                        "old"   => $this->dbInfo["db"],
                        "new"   => null,
                    ],
                ];

                $writes     = [];
                $charset    = false;
                $env        = "";
                $auth       = [];

                
                //Environment
                $environments = ["development", "testing", "production"];  
                

                $read = false;
                while(!$read){
                    $prompt = $this->color("Please select the environment to be initialized:\n\t1.Development\n\t2.Testing\n\t3.Production\n\nYour current environment is: '".env("ENVIRONMENT")."'", "green", "black");
                    $input  = $this->readLine($prompt);  
                    if(!($input <= 3 && $input > 0)){
                        $this->error(["Please use the range 1 - 3 to select the environment. You supplied '{$input}' which is not in the range", "", " Please make a valid a selection"], false);
                    }else{
                        $read = true;
                    }
                }

                $env = $environments[$input-1];
                
                
                //Database user
                $answered = false;
                while(!$answered){
                    $msg = "Your database username is set to: '{$this->dbInfo["user"]}'. Do you want to use this set username? Y or N";
                    $prompt = $this->color($msg, "green", "black");
                    $input  = $this->readLine($prompt);  
                    $input  = strtolower($input);
                    if($input != "n" && $input != "y"){
                        echo "Please press 'Y' for yes and 'N' for no\n";
                        continue;
                    } 
                    if($input == "n"){//
                        //Ask for new database user
                        $read = false;
                        while(!$read){
                            $prompt2    = $this->color("Please type in the database username you would like to use (Alphanumeric only)", "green", "black");
                            $input2     = $this->readLine($prompt2);  
                            if(!$this->validate("alphaNum", $input2)){
                                $this->error(["Database username must be alphanumeric only. The supllied database username '{$input2}' is not all alphanumeric.", "", " Please provide a valid name"], false);
                                echo "\n";
                                continue;
                            }else{
                                $read = true;
                            }
                        }
                        
                        $parsedDbInfo["user"]["new"] = $input2;
                        $auth["user"] = $input2;
                        array_push($writes, "user");
                    }else{
                        $auth["user"] = $this->dbInfo["user"];
                    }
                    $answered     = true;
                }
                                        
                //Database
                if(strlen($this->dbInfo["db"]) > 0){//has database set
                    $answered = false;
                    while(!$answered){
                        $prompt = $this->color("Your database is set to: '{$this->dbInfo["db"]}'. Do you want to use this set database? Y or N ", "green", "black");
                        $input  = strtolower($this->readLine($prompt));  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 

                        if($input == "n"){//
                            //Ask for new database
                            $read = false;
                            while(!$read){
                                $prompt2    = $this->color("Please type in the database name you would like to use (Alphanumeric, including ['-','_'])", "green", "black");
                                $input2     = $this->readLine($prompt2);  
                                
                                if(!$this->validate("name", $input2)){
                                    $this->error(["Database name must be alphanumeric including the characters['-','_']. The supllied database name '{$input2}' contains invalid character(s).", "", " Please provide a valid name"], false);
                                    echo "\n";
                                    continue;
                                }else{
                                    $read = true;
                                }
                            }
                    
                            $parsedDbInfo["database"]["new"] = $input2;
                            $auth["database"] = $input2;
                            array_push($writes, "database");
                        }else{
                            $auth["database"] = $this->dbInfo["db"];
                        }
                        $answered = true;
                    }
                }else{
                    //Ask for new database
                    $read = false;
                    while(!$read){
                        $prompt     = $this->color("No database is set. Would you like to set the database to work with? Y or N", "green", "black");
                        $input      = strtolower($this->readLine($prompt));  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 

                        if($input == "y"){
                            $subRead = false;
                            while(!$subRead){
                                $prompt2    = $this->color("Please type in the database name you would like to use (Alphanumeric, including ['-','_']): ", "green", "black");
                                $input2     = $this->readLine($prompt2);  
                                if(!$this->validate("name", $input2)){
                                    $this->error(["Database name must be alphanumeric including the characters['-','_']. The supllied database name '{$input2}' contains invalid character(s).", "", " Please provide a valid name"], false);
                                    echo "\n";
                                    continue;
                                }else{
                                    $parsedDbInfo["database"]["new"] = $input2;
                                    $auth["database"] = $input2;
                                    array_push($writes, "database");
                                    $subRead  = true;
                                }
                            }
                        }else{
                            echo "You have not specified the database to be initialized.\n";
                            continue;
                        }
                        $read = true;
                    }
                }
                
                //Password
                if(strlen($this->dbInfo["pass"]) > 0){//has database password set
                    $answered = false;
                    while(!$answered){
                        $prompt = $this->color("Your database password is set to: '{$this->dbInfo["pass"]}'. Do you want to use this set password? Y or N", "green", "black");
                        $input  = strtolower($this->readLine($prompt)); 
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        
                        if($input == "n"){//
                            //Ask for new database password
                            $read = false;
                            while(!$read){
                                $prompt2    = $this->color("Please type in the database password you would like to use", "green", "black");
                                $input2     = $this->readLine($prompt2); 

                                if(strlen($input2) < 1){
                                    $this->error(["You provided an empty password", "", ""], false);
                                    echo "\n";
                                    continue;
                                }
                                $parsedDbInfo["pass"]["new"] = $input2;
                                $auth["password"] = $input2;
                                array_push($writes,"pass");
                                $read = true;
                            }
                        }else{
                            $auth["password"] = $this->dbInfo["pass"];
                        }
                        $answered = true;
                    }
                }else{
                    //Ask for new database user password
                    $read = false;
                    while(!$read){
                        $prompt    = $this->color("No database password is set. Would you like to set password for database? Y or N", "green", "black");
                        $input  = strtolower($this->readLine($prompt));  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 

                        if($input == "y"){
                            $subRead = false;
                            while(!$subRead){
                                $prompt2    = $this->color("Please type in the database password you would like to use", "green", "black");
                                $input2     = $this->readLine($prompt2); 

                                if(strlen($input2) < 1){
                                    $this->error(["You provided an empty password", "", " Press 'C' to skip"], false);
                                    echo "\n";
                                    continue;
                                }

                                if(strtolower($input2) == "c"){
                                    $subRead = true;
                                    continue;
                                }


                                //validate password
                                if(!$this->validateDBPassword(env("DB_HOST"), $auth["user"], $input2)){
                                    $this->error(["The password '{$input2}' is incorrect for the user '{$auth["user"]}'. Try again", "", ""], false);
                                    continue;
                                };

                                $parsedDbInfo["pass"]["new"] = $input2;
                                array_push($writes,"pass");
                                $subRead = true;
                            }
                        }else{
                                //validate password
                                if(!$this->validateDBPassword(env("DB_HOST"), $this->dbInfo["user"], "")){
                                    $this->error(["Authentication has failed for the user '{$this->dbInfo["user"]}' with no password set. Please try again", "", ""], false);
                                    continue;
                                };
                                $auth["password"] = $this->dbInfo["pass"];
                        }
                        $read = true;
                    }
                }

                //Character set
                if(strlen($this->dbInfo["charset"]) > 0){//has database character-set set
                    $answered = false;
                    while(!$answered){
                        $prompt = $this->color("Your database character set is set to: '{$this->dbInfo["charset"]}'. Do you want to use this character set? Y or N", "green", "black");
                        $input  = strtolower($this->readLine($prompt)); 

                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 

                        if($input == "n"){//
                            //Ask for new database character set
                            $prompt2        = $this->color("Please type in the database character set you would like to use", "green", "black");
                            $input2         = $this->readLine($prompt2);
                            if(strlen($input2) < 1){
                                $this->error(["You provided an empty character set", "", ""], false);
                                echo "\n";
                                continue;
                            }
                            $parsedDbInfo["charset"]["new"] = $input2;
                            $charset = $input2;
                            array_push($writes, "charset");
                        }else{
                            $charset = $this->dbInfo["charset"];
                        }
                        $answered = true;
                    }
                }else{//No character-set set
                    //Ask for new database character set
                    $read = false;
                    while(!$read){
                        $prompt    = $this->color("No database character set is set. Would you like to set one now? Y or N", "green", "black");
                        $input  = strtolower($this->readLine($prompt));  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        }
                        if($input == "y"){
                            $subRead = false;
                            while(!$subRead){
                                $prompt2    = $this->color(" Please type in the database character set to use : ", "green", "black");
                                $input2     = $this->readLine($prompt2); 

                                if(strlen($input2) < 1){
                                    $this->error(["You provided an empty password", "", ""], false);
                                    echo "\n";
                                    continue;
                                }

                                $parsedDbInfo["charset"]["new"] = $input2;
                                $charset = $input2;
                                array_push($writes,"charset");
                                $subRead = true;
                            }
                        }
                        $read = true;
                    }
                }
            
    
                if(count($writes) > 0) $this->writeDatabaseInfo($writes, $parsedDbInfo);
            

                $db         = $auth["database"];
                $user       = $auth["user"];
                $pass       = $auth["password"];
                
                $databaseExist  = $this->databaseExist($this->dbInfo["db"]);
            
                $msg = "\nDatabase initialized successfuly. \n";  
                
                if(!$databaseExist){
                    $this->configs->pdo->query("CREATE DATABASE IF NOT EXISTS `{$db}`");
                    $msg .= "\tDatabase (New)\t\t = {$db}\n";
                }else{
                    $msg .= "\tDatabase (Existing)\t = {$db}\n";
                }
                                
                
                $msg .= "\tDatabase username        = {$user}\n";
                $msg .= "\tDatabase password        = {$pass}\n";
                $msg .= "\tDatabase character set   = {$charset}\n";
                
                echo $msg;
            break;
        }
    }
    public function use($object){

        global $argv;

        switch (strtolower($object)) {
            case 'db': 
                if(!isset($argv[2])){
                    $this->error(["The Database name must be supplied, please supply the name of the database name to use", "", ""]);
                } 
                $name = $argv[2];
                if(!$this->validate("name", $name)){
                    $this->error(["The supplied database name '{$name}' is invalid. Please specify a valid name", "", ""]);
                }
                
                $exist = $this->databaseExist($name);
                $parsedDbInfo     = [
                    "pass"      =>[
                        "old"   => $this->dbInfo["pass"],
                        "new"   => null
                    ],
                    "user"      =>[
                        "old"   => $this->dbInfo["user"],
                        "new"   => null,
                    ],
                    "charset"   =>[
                        "old"   => $this->dbInfo["charset"],
                        "new"   => null,
                    ],
                    "database"   =>[
                        "old"   => $this->dbInfo["db"],
                        "new"   => null,
                    ],
                ];
                
                if($exist){ // use it
                    if($name == getenv("DB_DATABASE")){
                        $this->warning(["The database: ", "'{$name}'", "is already set"]);
                    }else{
                        $parsedDbInfo["database"]["new"] = $name;
                        $write = $this->writeDatabaseInfo(["database"], $parsedDbInfo);
                        if($write["status"]){
                            $this->success(["The database '{$name}' has been set for use ","",""]);
                            $answered =true;
                        }
                    }
                }else{// not exist
                    $answered = false;
                    while(!$answered){
                        $prompt    = $this->color("The database '{$name}' does not exist. Do you want to create and use it? Y or N", "green", "black");
                        $input  = strtolower($this->readLine($prompt));  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        }
                        if($input == "y"){
                            $createDb = $this->executeCreateDb($name);
                            if($createDb){
                                $parsedDbInfo["database"]["new"] = $name;
                                $write = $this->writeDatabaseInfo(["database"], $parsedDbInfo);
                                if($write["status"]){
                                    $this->success(["The database '{$name}' has been created and set for use ","",""]);
                                    $answered =true;
                                }
                            }
                        }else{
                            $answered =true;
                        }
                    }
                }
            break;
        }
    }
    public function current($object){
        switch (strtolower($object)) {
            case 'db': 
                $this->pcd();
            break;
        }
    }
    public function export($object){

        global $argv;

        switch (strtolower($object)) {
            case 'data': 
                if(!isset($argv[2])){
                    $this->error(["The name of the table to be exported must be supplied, please supply the name of the table.", "", ""]);
                } 
                $name = $argv[2];
                if(!$this->validate("name", $name)){
                    $this->error(["The supplied table name '{$name}' is invalid. Please specify a valid name", "", ""]);
                }

                //check if table exist
                $exist = $this->configs->db->tableExist($name);
                if($exist["rowCount"] > 0){//export
                    
                    $exportData = $this->exportData($name);

                    $file = $this->configs->dataDir."/".$name.$this->configs->dataFileSuffix.".zld.sql";
                
                    if($exportData["status"]){
                        $this->success([$exportData["total"]. pluralizer(" record", "s:-1",$exportData["total"])." ".pluralizer("has", "have", $exportData["total"])." been exported to the data file ", $file, ""]);
                    }else{
                        $this->warning(["The table '{$name}' is empty, no data to export", "", ""]);
                    }

                }else{//no table found
                    $this->error(["The table ", $name, " does not exist"]);
                }

            break;
        }
    }
    public function import($object){//from project to database

        global $argv;

        switch (strtolower($object)) {
            case 'data': 
                if(!isset($argv[2])){
                    $this->error(["The name of the table to be filled with data must be supplied, please supply the name of the table.", "", ""]);
                } 

                $names          = $argv[2];

                $allDataFiles   = explode(",", $names);

                $parsedFiles    = []; 

                foreach ($allDataFiles as $dataFileName) {
                    $dataFileName = trim($dataFileName);
                    
                    if(!$this->validate("name", $dataFileName)){
                        $this->error(["The supplied data file name '{$dataFileName}' is invalid. Please specify a valid name", "", ""]);
                    }

                    //Check if datafile for table exist
                    $dataFile = $this->configs->dataDir."/".$dataFileName.$this->configs->dataFileSuffix.".zld.sql";
                    if(!file_exists($dataFile)){
                        $this->error(["The Data file ", $dataFile, " not found"]);
                    }

                    array_push($parsedFiles, $dataFile);
                }

                
                //Begin importing

                foreach ($parsedFiles as $parsedFile) {
                    $importData = $this->importData($parsedFile);

                    if($importData["code"] == "c0"){//imported
                        $this->success([$importData["totalInserted"]." ".pluralizer("record", "s:-1", $importData["totalInserted"])." ".pluralizer("has", "have", $importData["totalInserted"])." been imported succefully to the table: ","","'".$importData["tableName"]."'"], false);
                    }else if($importData["code"] == "c1") {//no table found
                        $this->error(["The table ", $importData["tableName"], " does not exist"], false);
                    }else if($importData["code"] == "c2"){
                        $this->error(["Error occured during data importation","",""], false);
                    }else if($importData["code"] == "c3"){
                        $this->error(["Table for the data file '$parsedFile' is not tracked. Run the command: ","php zlight build:schema tableName",""], false);
                    }
                }

            break;
        }
    }
    public function seed($object){

        global $argv;

        switch (strtolower($object)) {
            case 'db': 
                if(!isset($argv[2])){
                    $this->error(["The seeder name must be supplied, please supply the name of the seeder ", "", ""]);
                } 

                $seederName = $argv[2];
                if(!$this->validate("name", $seederName)){
                    $this->error(["The supplied seeder name '{$seederName}' is invalid. Please specify a valid name", "", ""]);
                }

                //Check if seeder file for table exist
                $seederFile = $this->configs->seederDir."/".$seederName.".php";

                if(!file_exists($seederFile)){
                    $this->error(["The seeder file ", $seederFile, " does not not exist, try creating one with the 'create:seeder' command"]);
                }

                //import the seeder file
                require($seederFile);

                /**
                 * $data        : Holds the data to seed
                 * $recordSize  : Holds the record size to be seeded
                 * $tableName   : Holds the table name to perform seeding on
                 */

                //Execute seeding
                $exec = $this->app->db->seed($tableName)->data($data, $recordSize);
                
                if($exec){
                    $this->success([$recordSize.pluralizer(" record", "s:-1", $recordSize)." ".pluralizer("has", "have", $recordSize)." been seeded succefully to the table: ","","'".$tableName."'"], false);
                }else{
                    $this->error(["Error occured while seeding data to the table: ", $tableName, ""]);
                }
                
            } 
    }
}
?>