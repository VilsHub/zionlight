<?php
class Console extends CLIColors{
    private $version  = "1.0.0.beta";
    private $appConfig;
    private $commands =[
        "create" => [
            "middleware",
            "controller",
            "model",
            "queriesbank",
            "service",
            "schema",
            "database"
        ],
        "shine" => 1,
        "build" => [
            "schema",
        ],
        "reset" => [
            "schema"
        ],
        "delete" => [
            "schema"
        ],
        "untrack" => [
            "schema"
        ],
        "track" => [
            "schema"
        ],
        "delete" => [
            "schema"
        ],
        "initialize" => [
            "database"
        ],
        "use" => [
            "database"
        ],
        "current" => [
            "database"
        ],
        "export" =>[
            "data"
        ],
        "import" =>[
            "data"
        ]
    ];
    private $argv;
    function __construct($argv, $argc){
        $this->appConfig    = require("config/app.php");
        $this->argv         = $argv;
        $this->dbInfo       = require("config/dbDetails.php");
        
        parent::__construct();

        if($argc <= 1){
            $this->showAllOptions();
        }else{
            //call command manager
            $this->commandManager($argv[1]);
        }
    }

    // CLI
    private function showAllOptions(){
        $header = "   Welcome to Zion Light CLI version ".$this->version."   ";
        $margin = $this->getMargin(strlen($header))."\n";       
        echo "\n";
        $this->write($margin, "white", "blue");
        $this->write($header."\n", "white", "blue");
        $this->write($margin, "white", "blue");
        echo "\n\n";
        echo "Here are the things your can do:";
        $list = "\n".$this->color(" CREATE", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Create controller", "yellow", "black")."\t".$this->color("create:controller", "green", "black")." controllerName";
        $list .= "\n\t".$this->color(" - Create model", "yellow", "black")." \t".$this->color("create:model", "green", "black")." ModelName";
        $list .= "\n\t".$this->color(" - Create query bank", "yellow", "black")." \t".$this->color("create:queryBank", "green", "black")." querybankName";
        $list .= "\n\t".$this->color(" - Create middleware", "yellow", "black")." \t".$this->color("create:middleware", "green", "black")." middlewareName";
        $list .= "\n\t".$this->color(" - Create schema", "yellow", "black")." \t".$this->color("create:schema", "green", "black")." schemaName";
        $list .= "\n\t".$this->color(" - Create database", "yellow", "black")." \t".$this->color("create:database", "green", "black")." databaseName";
        echo "\n\n";
        
        $list .= "\n".$this->color(" SHINE", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Start server", "yellow", "black")." \t".$this->color("shine:@", "green", "black")."portNumber";

        $list .= "\n".$this->color(" DELETE", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Delete schema", "yellow", "black")." \t".$this->color("delete:schema", "green", "black")." schemaName";

        $list .= "\n".$this->color(" RESET", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Reset schema", "yellow", "black")." \t".$this->color("reset:schema", "green", "black")." [-r | -r schemaName | schemaName]";
        
        $list .= "\n".$this->color(" TRACK", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Track schema", "yellow", "black")." \t".$this->color("track:schema", "green", "black")." schemaName";
        
        $list .= "\n".$this->color(" UNTRACK", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Untrack schema", "yellow", "black")." \t".$this->color("untrack:schema", "green", "black")." schemaName";
        
        $list .= "\n".$this->color(" BUILD", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Build schema", "yellow", "black")." \t".$this->color("build:schema", "green", "black")." [-init | -new | schemaName]";

        $list .= "\n".$this->color(" USE", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Use database", "yellow", "black")." \t".$this->color("use:db", "green", "black")." databaseName";
        
        $list .= "\n".$this->color(" IMPORT", "light_purple", "black");       
        $list .= "\n\t".$this->color(" - Import  data", "yellow", "black")." \t".$this->color("import:data", "green", "black")." dataFileName";

        $list .= "\n".$this->color(" EXPORT", "light_purple", "black");       
        $list .= "\n\t".$this->color(" - Export  data", "yellow", "black")." \t".$this->color("export:data", "green", "black")." tableName";
        
        $list .= "\n".$this->color(" CURRENT", "light_purple", "black");       
        $list .= "\n\t".$this->color(" - View current DB", "yellow", "black")." \t".$this->color("current:database", "green", "black");

        echo $list;
        echo "\n\n";
    }
    private function commandManager($command){
        $CommandInfo = $this->getAction($command);
        $exec = $CommandInfo["command"];
        $this->validateCommand($exec);
        if(strtolower($exec) != "initialize") $this->databaseInitCheck($this->dbInfo["db"]);
        switch (strtolower($exec)){
            case 'create':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->create($action);
                break;
            case 'shine':
                $this->startServer();
                break;
            case 'build':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->build($action);
                break;
            case 'reset':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->reset($action);
                break;
            case 'untrack':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->untrack($action);
                break;
            case 'track':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->track($action);
                break;
            case 'delete':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->delete($action);
                break;
            case 'initialize':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->initialize($action);
                break;
            case 'use':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->use($action);
                break;
            case 'current':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->current($action);
                break;
            case 'export':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->export($action);
                break;
            case 'import':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->import($action);
                break;
            default:
                # code...
                break;
        }
    }
    private function getAction($command){
        $info = explode(":", $command);
        $build = ["command" => $info[0]];
        
        if(isset($info[1])){
            $build["action"] = $info[1];
        }else{
            $build["action"] = null;
        }

        return $build;
    }
    private function validateCommand($command){
        $command = strtolower($command);
        if (!array_key_exists($command, $this->commands)){
            $this->error(["The command: ", $command, " is not supported"]);
        }
    }
    private function validateCommandAction($command, $action){
        $action = strtolower($action);
        $totalCommandAction = count($this->commands[$command]);
        if($action == null){
            $this->error(["No ".strtoupper($command)." command task supplied. You have not specified any task. The supported ".pluralizer("task", "s:-1", $totalCommandAction)." for ".pluralizer("this", "ese:2", $totalCommandAction)." ".pluralizer("command", "s:-1", $totalCommandAction)." ".pluralizer("is", "are", $totalCommandAction).": ". implode(", ", $this->commands[$command] ).". Example ", $command.":".$this->commands[$command][0], ""]);
        }else if(!in_array($action, $this->commands[$command])){
            $this->error(["The ".strtoupper($command)." command action: ", $action, " is not supported"]);
        }
    }
    private function error($details, $kill=true){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $padding = "   ";
        $margin = $this->getMargin($chars+6);
        $this->write($margin, "white", "red");
        echo "\n";
        $this->write($padding.$details[0], "white", "red");
        $this->write($details[1], "yellow", "red");
        $this->write($details[2].$padding, "white", "red");  
        echo "\n";
        $this->write($margin, "white", "red");
        echo "\n";
        if($kill) die("\n\n");
    }
    private function success($details, $kill=true){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $padding = "   ";
        $margin = $this->getMargin($chars+6);
        $this->write($margin, "white", "green");
        echo "\n";
        $this->write($padding.$details[0], "white", "green");
        $this->write($details[1], "yellow", "green");
        $this->write($details[2].$padding, "white", "green");  
        echo "\n";
        $this->write($margin, "white", "green");
        echo "\n";
        if($kill) die("\n\n");
    }
    private function warning($details){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $padding = "   ";
        $margin = $this->getMargin($chars+6);
        $this->write($margin, "black", "yellow");
        echo "\n";
        $this->write($padding.$details[0], "black", "yellow");
        $this->write($details[1], "blue", "yellow");
        $this->write($details[2].$padding, "black", "yellow");  
        echo "\n";
        $this->write($margin, "black", "yellow");
        echo "\n";
    }
    private function info($details){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $padding = "   ";
        $margin = $this->getMargin($chars+6);
        $this->write($margin, "white", "blue");
        echo "\n";
        $this->write($padding.$details[0], "white", "blue");
        $this->write($details[1], "yellow", "blue");
        $this->write($details[2].$padding, "white", "blue");  
        echo "\n";
        $this->write($margin, "white", "blue");
        echo "\n";
    }
    private function getMargin($n){
        $space = "";
        for ($x=0; $x<$n; $x++){
            $space .= html_entity_decode("&nbsp;");
        }
        return $space;
    }
    private function validate($type, $value){
        switch ($type) {
            case 'alpha':
                return preg_match("/^[a-zA-Z]+$/", $value);
                break;
            case 'alphaNum':
                return preg_match("/^[a-zA-Z0-9]+$/", $value);
                break;
            case 'name':
                return !preg_match("/[^a-zA-Z0-9\_\-]+/", $value);
                break;

            default:
                # code...
                break;
        }
    }
    private function readLine($prompt){
        echo $prompt." : ";
        $callForInput = fopen("php://stdin", "r");
        $response = trim(fgets($callForInput));
        fclose($callForInput);
        return $response;
    }
    private function buildTemplate($type){
        $name = ucwords($this->argv[2]);
        switch ($type) {
            case 'controller':
                $dir = $this->appConfig->controllersDir;
                $controllerContent =  $this->getTemplate("class", "controller", $name, [9]);

                //write to new controller file
                $newControllerFile = $dir."/".$name.".php";

                if($this->writeTemplate($newControllerFile, $controllerContent, "Controller")){
                    $this->success(["The controller: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'model':
                $dir = $this->appConfig->modelsDir;
                $modelContent =  $this->getTemplate("class", "model", $name.$this->appConfig->modelFileSuffix, [7]);

                //write to new model file
                $newModelFile = $dir."/".$name.$this->appConfig->modelFileSuffix.".php";
                if($this->writeTemplate($newModelFile, $modelContent, "Model")){
                    $this->success(["The Model: ",$name.$this->appConfig->modelFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'middleware':
                $dir = $this->appConfig->middlewaresDir;
                $middlewareContent =  $this->getTemplate("class", "middleware", $name, [6]);

                //write to new middleware file
                $newMiddlewareFile = $dir."/".$name.".php";
                if($this->writeTemplate($newMiddlewareFile, $middlewareContent, "Middleware")){
                    $this->success(["The Middleware: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'queriesbank':
                $dir = $this->appConfig->queriesBankDir;
                $queryBankContent =  $this->getTemplate("class", "queriesbank", $name.$this->appConfig->queryFileSuffix, [6]);

                //write to new middleware file
                $newQuerybankFile = $dir."/".$name.$this->appConfig->queryFileSuffix.".php";
                if($this->writeTemplate($newQuerybankFile, $queryBankContent, "QueriesBank")){
                    $this->success(["The QueryBank: ",$name.$this->appConfig->queryFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'service':
                $dir = $this->appConfig->servicesDir;
                $serviceContent =  $this->getTemplate("class", "service", $name.$this->appConfig->serviceFileSuffix, [10]);

                //write to new service file
                $newServiceFile = $dir."/".$name.$this->appConfig->serviceFileSuffix.".php";
                if($this->writeTemplate($newServiceFile, $serviceContent, "Service")){
                    $this->success(["The Service: ", $name.$this->appConfig->serviceFileSuffix, " has been created successfully in the directory: ".$dir]);
                }

                break;
            case 'schema':
                $this->setupCheck();
                $dir = $this->appConfig->schemaDir;
                $tableName = "";
                $schemaName="";
                
                //write to new schema file
                $newSchemaFile = $dir."/".strtolower($this->argv[2]).".sql";
                $supportedDDS = ["-create.table", "-alter.table", "-rename.table"];
               
                if(!isset($this->argv[3])){
                    $this->error(["Please specify the table name or definition type. Example: ", "create:schema test sample | create:schema test -create.table sample ",""]);
                }

                $this->argv[3] = strtolower($this->argv[3]);
                
                //set type
                if(in_array($this->argv[3], $supportedDDS)){ //needs schema template
                    $objectType = ucwords(explode(".", $this->argv[3])[1]);
                    if(!isset($this->argv[4])){ //Must supply object name
                        $this->error(["No {$objectType} name given. Please specify one", "", ""]);
                    }else{
                        //validate object name
                        if(!$this->validate("name", $this->argv[4])){
                            $this->error(["The supplied {$objectType} name '{$this->argv[4]}' is invalid. Please specify a valid name", "", ""]);
                        }
                    }
                    
                    $schemaTemplateName = ltrim($this->argv[3], "-");
                    $tableName = $this->argv[4];
                }else{
                    //validate object name
                    if(!$this->validate("name", $this->argv[3])){
                        $this->error(["The supplied {$objectType} name '{$this->argv[4]}' is invalid. Please specify a valid name", "", ""]);
                    } 
                    $schemaTemplateName = "empty.table";
                    $tableName = $this->argv[3];
                }    
  
                $schemaName = $this->argv[2];

                //check file if exist
                if(file_exists($newSchemaFile)){
                    $answered =false;
                    while(!$answered){
                        $prompt = $this->color(" The schema file: '", "black", "yellow"). $this->color($this->argv[2].".sql", "blue", "yellow").  $this->color("' exist, do you want to override? Y or N ", "black", "yellow");
                        $input =  $this->readLine($prompt);
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        if(strtolower($input) == "y"){
                            $write = $this->writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, [1,2]);
                            if($write){
                                $this->success(["The Schema file: '".$name.".sql' has been created successfully in the directory: '{$dir}'. Complete your schema structure and run: ", "build:schema",""]);
                            }
                            $answered = true;
                        }else{
                            $answered = true;
                        }
                    }
                }else{
                    $write = $this->writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, [1,2]);
                    if($write){
                        $this->success(["The Schema file: '".$name.".sql' has been created successfully in the directory: '{$dir}'. Complete your schema structure and run: ", "build:schema",""]);
                    }
                }
                break;
            default:
                # code...
                break;
        }
        
    }
    private function writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, $points){
        $schemaContent =  $this->getTemplate("schema", $schemaTemplateName, $tableName, $points);
        if($this->executeWrite($newSchemaFile, $schemaContent)){
            //track schema file
            return $trackSchema = $this->trackCreatedSchema($schemaName, $tableName);
        }
    }
    private function executeWrite($fileName, $content){
        $fileHandler = fopen($fileName, "w");
        if (fwrite($fileHandler, $content) > 0){
            return true;
        }else{
            return false;
        };
    }
    private function getTemplate($templateType, $templateName, $placeholderName=null, $placeholderLine){
        $file = "";
        $contents = "";
        $n = 0;
        $dir = $this->appConfig->appRootDir."app/assets/templates/";
        $placeholder = "";
        if($templateType == "class"){
            $dir .= "classes/";
            $placeholder = "className";
        }else if($templateType == "schema"){
            $dir .= "schemas/";
            $placeholder = "tableName";
        }

        $file = $dir.$templateName.".zlt";

        $fileHandler = fopen($file, "r");
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if(in_array($n+1, $placeholderLine)){
                $contents .= str_replace($placeholder, $placeholderName, $line);
            }else{
                $contents .= $line;
            }
            $n++;
        }
        fclose($fileHandler);
        return $contents;
    }
    private function getEnvFile($dbState){
        $contents = "";
        $fileHandler = fopen(".env", "r");
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
    private function getDataFileContents($dataFileName){
        $data = require ($this->appConfig->dataDir."/".$dataFileName.$this->appConfig->dataFileSuffix.".zld");
        $contents = "";
        $values = [];
        
        foreach ($data as $row) {
            $totalColums = count($row);
            $x=0;
            $rowData = "(";
            foreach ($row as $key => $cell) {
                if($x == $totalColums-1){//last row
                    if($cell != NULL){
                        $rowData .= "'".trim($cell)."'".")";
                    }else{
                        $rowData .= "NULL".")";
                    }
                }else{
                    if($cell != NULL){
                        $rowData .= "'".trim($cell)."'".", ";
                    }else{
                        $rowData .= "NULL".", ";
                    }
                }
                $x++;
            }
            array_push($values, $rowData);
        }

        $contents = implode(",\n", $values);
        // die($contents);
        return [
            "total" => count($values),
            "content" => $contents
        ];
    }
    private function insertData($schemaName, $tableName, $insertQuery){
        //check if empty
        $reset = $this->resetSchema($schemaName, $tableName, true);
        if($reset["code"] == "r2"){ //reset and built successfully
            //insert data
            $this->appConfig->db->disableForeignKeyCheck();
            $run = $this->appConfig->db->run($insertQuery);
            $this->appConfig->db->enableForeignKeyCheck();
            return $run;
        }
    }
    private function exportData($tableName){
        $dataFile = $this->appConfig->dataDir."/".$tableName.$this->appConfig->dataFileSuffix.".zld";
        $contents = '<?php'."\n";
        $contents .= '$data = ['."\n";
        $sql = "SELECT * FROM `{$tableName}`";
        $run = $this->appConfig->db->run($sql);
        $records = 0;
        

        if($run["rowCount"] > 0){
            if($run["rowCount"] > 1){
                foreach ($run["data"] as $tableRow) {
                    $line = "   [";
                    $parsedData = array_map(function($cell){
                        if(strlen($cell) > 0){
                           return "\"".addSlashes($cell)."\""; 
                        }else{
                            return "NULL";
                        }   
                    }, $tableRow);
                    $line .= implode(", ", $parsedData)."],\n";
                    $contents .= $line;
                    $records++;
                }
            }else{
                $line = "   [";
                $parsedData = array_map(function($cell){
                    if(strlen($cell) > 0){
                        return "\"".$cell."\""; 
                     }else{
                         return "NULL";
                     }
                }, $run["data"]);

                $line .= implode(", ", $parsedData)."],\n";
                $contents .= $line;
                $records++;
            }
            
            $contents .= '];'."\n";
            $contents .= 'return $data;'."\n";
            $contents .= '?>';

            //write content to file
            $this->executeWrite($dataFile, $contents);
            
            return [
                "status"=> true,
                "exported"=>$records,
                "total" => $run["rowCount"]
            ];
        }else{
            return [
                "status"=> false,
                "exported"=>0,
                "total" => 0
            ];
        }
    }
    private function importData($tableName){
        $values = "";
        $records = 0;
        $insertSQL = "INSERT INTO `{$tableName}` (";

        $answered = false;
        while (!$answered) { 
            $prompt = $this->color(" You are about to empty the table: '$tableName' and fill it with new data. Do you proceed? Y or N ", "blue", "yellow");
            $input =  $this->readLine($prompt);  
            if($input != "n" && $input != "y"){
                echo "Please press 'Y' for yes and 'N' for no\n";
                continue;
            } 

            if(strtolower($input) == "y"){
                //get table column names
                $sql = "DESCRIBE `{$tableName}`";
                $run = $this->appConfig->db->run($sql);
                $n=0;
                foreach ($run["data"] as $row) {
                    $n++;
                    if($run["rowCount"] == $n){
                        $insertSQL .= "`".$row["Field"]."`) VALUES\n";
                    }else{
                        $insertSQL .= "`".$row["Field"]."`,";
                    }
                }
    
                //build values
                $data = $this->getDataFileContents($tableName);
               
                $insertQuery = $insertSQL.$data["content"];
                //get schema name
                $schemaName = $this->getTableSchemaName($tableName)["data"]["schema_name"];
                
                if($data["total"] > 0){//has data to import
                    //insert values by running the insert query
                    $insertData = $this->insertData($schemaName, $tableName, $insertQuery);

                    if($insertData["status"]){//imported
                        $this->success([$insertData["rowCount"]."/".$data["total"]." ".pluralizer("record", "s:-1", $insertData["rowCount"])." ".pluralizer("has", "have", $insertData["rowCount"])." been imported succefully to the table: ","","'".$tableName."'"]);
                    }else{//could not import data
                        $this->error(["Error occured during data importation","",""]);
                    }
                }else{//no data to import
                    $this->warning(["Data file is empty. Nothing to import. Try exporting to the data file, use the coomand: ","export:data",""]);
                }
                $answered = true;
            }else{
                $answered = true;
            }
        }
    }
    // CLI ends

    // Schema starts
    private function getSchema($name){
        $schemaFile = $this->appConfig->schemaDir."/".$name.".sql";
        $data = $this->readSchemaContent($schemaFile);
        if(file_exists($schemaFile)){
            return [
                "data"      => $data["schema"],
                "status"    => true ,
                "tableName" => $data["tableName"]
            ];
        }else{
            return [
                "data"      => null,
                "status"    => false,
                "tableName" => null
            ];
        }
    }
    private function readSchemaContent($file){
        $fileHandler = fopen($file, "r");
        $content = "";
        $tableName = "";
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if(strpos($line, "name:") !== false){//located file name
                $tableName = trim(explode(":", $line)[1]);
            }
            $content .= $line;   
        }
        
        fclose($fileHandler);
        return [
            "tableName" => $tableName,
            "schema"    => $content
        ];
    }
    private function deleteSchemaFile($name){
        $schemaFile = $this->appConfig->schemaDir."/".$name.".sql";
        if(file_exists($schemaFile)){
            return unlink($schemaFile);
        }else{
           return false;
        }
    }
    private function createTrackTable(){
        $sql = "CREATE TABLE `zlight_schema_track` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `schema_name` VARCHAR(50) NOT NULL UNIQUE,
            `table_name` VARCHAR(50) NOT NULL UNIQUE,
            `build` ENUM ('0', '1') DEFAULT '0'
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        return $run = $this->appConfig->db->run($sql);
    }
    private function buildStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '1'";
        $run = $this->appConfig->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function resetStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '0'";
        $run = $this->appConfig->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $run = $this->appConfig->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackedFiles(){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track`";
        $run = $this->appConfig->db->run($sql);
        $names = [];
        if($run["rowCount"] == 1){
            foreach ($run["data"] as $key => $value) {
                array_push($names, $value.".sql");
            }
        }else if($run["rowCount"] > 1){
            foreach ($run["data"] as $key => $value) {
                array_push($names, $value["schema_name"].".sql");
            }
        }
        return $names;
    }
    private function trackCreatedSchema($name, $tableName){
        $trackStatus = $this->trackStatus($name);
        $tracked = false;
        if(!$trackStatus){// not tracked, so track
            $track = $this->trackSchema($name, $tableName);
            if($track["status"]) $tracked = true;
        }else{// tracked, just reset
            $reset = $this->resetSchema($name, $tableName, false);
            if($reset["code"] == "r1") $tracked = true;
        }
        return $tracked;
    }
    private function logSchema($name, $tableName){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $exist = $this->appConfig->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '1' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '1', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->appConfig->db->run($sql);
    }
    private function unlogSchema($name, $tableName){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $exist = $this->appConfig->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '0' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->appConfig->db->run($sql);
    }
    private function buildSchema($name){
        $schemaData     = $this->getSchema($name);
        $isBuilt        = $this->buildStatus($name);
        if($schemaData["status"]){
            //check build
            if(!$isBuilt){
                //build
                if(strlen($schemaData["data"]) == 0){//no data to be built
                    return [
                        "status"=>false,
                        "code"=>4 //no schema file content
                    ];
                }else{//has data to be built
                    try {
                        $this->appConfig->db->disableForeignKeyCheck();
                        $run = $this->appConfig->db->run($schemaData["data"]);
                        $this->appConfig->db->enableForeignKeyCheck();
                        if($run["status"]){
                            //log schema
                            $this->logSchema($name, $schemaData["tableName"]);
                            return [
                                "status"=>true,
                                "code"=>1
                            ];
                        }
                    }catch (\Throwable $th) {        
                        $errorMessage = "";
                        $errorMessage .= "Schema        : ". $name." \n";
                        $errorMessage .= "Error number  : ".$th->errorInfo[1]."\n";
                        $errorMessage .= "Error Message : ".$th->errorInfo[2]."\n";
                        $errorMessage .= "\n";
                        
                        $this->write("", "white", "red");
                        $this->write($errorMessage , "white", "red");
                        $this->write("", "white", "red");
                        die();
                    } 
                } 
            }else{
                return [
                    "status"=>false,
                    "code"=>3 //already built
                ]; 
            }       
        }else{
            return [
                "status"=>false,
                "code"=>2 //no schema file
            ];
        }
    }
    private function deleteSchema($name, $tableName){
        //reset schema without building
        $resetSchema = $this->resetSchema($name, $tableName, false);
        if($resetSchema["status"]){//reset succesfully, proceed to untrack from tracker and delete
            if($this->untrackSchema($name)){
                return $this->deleteSchemaFile($name);
            }
        }else{// was not tracked
            return $this->deleteSchemaFile($name);
        }
    }
    private function getTableSchemaName($tableName){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track` WHERE `table_name` = '{$tableName}'";
        return $this->appConfig->db->run($sql);
    }
    private function resetSchema($name, $tableName, $rebuild=true){
        //Check track State
        $trackState = $this->trackStatus($name);

        if($trackState){
            //reset
            try {
                $this->appConfig->db->disableForeignKeyCheck();
                $sql = "DROP TABLE IF EXISTS `{$tableName}`";
                $run = $this->appConfig->db->run($sql);
                $this->appConfig->db->enableForeignKeyCheck();
                
                if($run["status"]){
                    //reset in tracker
                    $this->unlogSchema($name, $tableName);
                    
                    if($rebuild){
                        $rebuildSchema = $this->buildSchema($name);
                        if($rebuildSchema["status"]){
                            if($rebuildSchema["code"] == 1){
                                return [
                                    "status"=>true,
                                    "code"=>"r2" // reset and built successfully 
                                ];
                            }
                        }
                    }else{
                        return [
                            "status"=>true,
                            "code"=>"r1"// reset successfully
                        ];
                    }
                }
            }catch (\Throwable $th) {  
                $errorMessage = "";
                $errorMessage .= "Schema        : ". $name." \n";
                $errorMessage .= "Error number  : ".$th->errorInfo[1]."\n";
                $errorMessage .= "Error Message : ".$th->errorInfo[2]."\n";
                $errorMessage .= "\n";
                
                $this->write("", "white", "red");
                $this->write($errorMessage , "white", "red");
                $this->write("", "white", "red");
                die();
            }  
            
        }else{
            return [
                "status" => false,
                "code" => "r4"
            ];
        }
    }
    private function getSchemaFiles(){
        $schemas = array_slice(scandir($this->appConfig->schemaDir), 2);
        $trackedFiles = $this->trackedFiles();
        return [
            "schemaFiles" => $schemas,
            "trackedFiles" => $trackedFiles
        ];
    }
    private function autoBuildSchema($mode="new"){
        $schemaData = $this->getSchemaFiles();
        $totalTracked = count($schemaData["trackedFiles"]);
        $totalSchema = count($schemaData["schemaFiles"]);
        $missingSchema = array_diff($schemaData["schemaFiles"], $schemaData["trackedFiles"]);
        $totalMissing = count($missingSchema);
        if($mode == "new"){//for newly created schema that are not tracked 
            foreach ($missingSchema as $key => $value) {
                $name = str_replace(".sql", "", $value);
                $state =  $this->buildSchema($name);
                if($state["status"]){
                    if($state["code"] == 1){
                        $this->success(["The schema: '{$name}' has been built successfully", "",""], false);
                    }
                }else{
                    if($state["code"] == 4){
                        $this->warning(["The schema file: '{$name}' is empty, found nothing to build", "",""]);
                    }
                }
            }
            
            if($totalMissing == 0){
                $this->warning(["The new schema found", "",""]);
            }
        }else if($mode == "tracked"){
            if($totalTracked>0){
                foreach ($schemaData["trackedFiles"] as $key => $value) {
                    $name = str_replace(".sql", "", $value);
                    $state =  $this->buildSchema($name);
                    if($state["status"]){
                        if($state["code"] == 1){
                            $this->success(["The schema: '{$name}' has been built successfully", "",""], false);
                        }
                    }else{
                        if($state["code"] == 2){
                            $this->error(["The schema: '{$name}' is not found, create one using the", " create:schema", " command"]);
                        }else if($state["code"] == 4){
                            $this->warning(["The schema file: '{$name}' is empty, found nothing to build", "",""]);
                        }else if($state["code"] == 3){
                            $this->warning(["The schema: '{$name}' has been built already. Use the command: ", " reset:schema"," to reset the schema if you want to build again"]);
                        }
                    }
                }
            }else{
                $this->warning(["No tracked schema found","",""]); 
            }
           
            if($totalMissing > 0){
                $this->warning(["The schema ".pluralizer("file","s:-1", $totalMissing).": '".implode(", ",$missingSchema)."' ".pluralizer("is","are", $totalMissing)." not tracked. If you added ".pluralizer("it","them", $totalMissing)." manually, add ".pluralizer("it","each", $totalMissing)." to tracker by using the:", " track:schema | build:schema -new ", "command"]);
            }
        }
    }
    private function autoResetSchema($rebuild=true){
        $answered = false;
        //Check if has built

        while(!$answered){
            $dLabel = $rebuild?" and rebuild again":"";
            $prompt = $this->color(" Are you sure you want to drop the entire schema {$dLabel}? Y or N ", "blue", "yellow");
            $input =  $this->readLine($prompt);
            
            if($input != "n" && $input != "y"){
                echo "Please press 'Y' for yes and 'N' for no\n";
                continue;
            } 

            if(strtolower($input) == "y"){
                $schemaData = $this->getSchemaFiles();
                $totalTracked = count($schemaData["trackedFiles"]);
                $totalSchema = count($schemaData["schemaFiles"]);
                $missingSchema = array_diff($schemaData["trackedFiles"], $schemaData["schemaFiles"]);
                $untrackedSchema = array_diff($schemaData["schemaFiles"], $schemaData["trackedFiles"]);
            
                if($totalTracked > 0){
                    foreach ($schemaData["trackedFiles"] as $key => $value) {
                        $name = str_replace(".sql", "", $value);
                        if(!$this->resetStatus($name)){//has not been reset
                            $state =  $this->resetSchema($name, $rebuild);
                            if($state["status"]){
                                if($state["code"] == "r2"){
                                    $this->success(["The schema: '{$name}' has been reset and built successfully", "",""], false);
                                }else if($state["code"] == "r1"){
                                    $this->success(["The schema: '{$name}' has been reset successfully without rebuilding","",""], false);
                                }
                            }
                        }else{//reset already
                            $this->warning(["The schema : '{$name}' has already been reset ","",""]);
                        }
                    }
                }else{
                    $this->warning(["No tracked schema found","",""]); 
                }
               

                $totalMissing = count($missingSchema);
                $totalUntracked = count($untrackedSchema);
                if($totalMissing > 0){
                    $this->warning(["The schema ".pluralizer("file","s:-1", $totalMissing).": '".implode(", ",$missingSchema)."' ".pluralizer("is","are", $totalMissing)." missing, if you deleted ".pluralizer("it","them", $totalMissing)." manually, clear ".pluralizer("it","each", $totalMissing)." from tracker by using the:", " untrack:schema ", "command"]);
                }
                if($totalUntracked > 0){
                    $this->warning(["The schema ".pluralizer("file","s:-1", $totalUntracked).": '".implode(", ",$untrackedSchema)."' ".pluralizer("is","are", $totalUntracked)." untracked, if you added ".pluralizer("it","them", $totalUntracked)." manually, add ".pluralizer("it","each", $totalUntracked)." to tracker by using the:", " track:schema | build:schema -new ", "command"]);
                }
                $answered = true;
            }else{
                $answered = true; 
            }
        }
          
    }
    private function untrackSchema($name){
        $sql = "DELETE FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        return $this->appConfig->db->run($sql);
    }
    private function trackSchema($name, $tableName){
        $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        return $this->appConfig->db->run($sql);
    }
    private function setupCheck(){
        $trackState = $this->appConfig->db->tableExist("zlight_schema_track");
        if($trackState["rowCount"] == 0){// create track table
            $this->createTrackTable();
        }
    }
    // Schema ends

    // Database starts
    private function setDbInit(){
        $updateEnvFileContent = $this->getEnvFile("true");
        return $this->executeWrite(".env", $updateEnvFileContent);
    }
    private function databaseInitCheck($name){
        if($this->dbInfo["isDatabaseApp"]){
            if(!$this->databaseExist($name)){
                $msg = $this->getMargin(105)."\n";
                $msg .= "  Database initialization has not been executed. Please run the command: ";
                $this->write($msg, "white", "red");
                $msg = "php zlight initialize:database  \n";
                $this->write($msg, "yellow", "red");
                $msg = $msg = $this->getMargin(105)."\n";
                $this->write($msg, "white", "red");
                die;
            }
        }
    }
    private function databaseExist($name){
        if(strlen($name) == 0){
            return false;
        }else{
            $sql = "SHOW DATABASES LIKE '{$name}'";
            $run = $this->appConfig->xDB->run($sql);
            return $run["rowCount"] > 0;
        }
    }
    private function executeCreateDb($name){
        $sql = "CREATE DATABASE `{$name}`";
        $run = $this->appConfig->db->run($sql);
        return $run["rowCount"] > 0;
    }
    private function createDatabase($name){
        if(!$this->validate("name", $name)){
            $this->error(["The supplied database name '{$name}' is invalid. Please specify a valid name", "", ""]);
        }

        $exist = $this->databaseExist($name);
        
        if(!$exist){ // create it
            $createDb = $this->executeCreateDb($name);
            if($createDb){
                $this->success(["The database :", $name, " has been created successfully"]);
            }else{
                $this->error(["Error encountered while creating the database: '{$name}'", "", ""]);
            }
        }else{
            $this->error(["The database '{$name}' already exist", "", ""]);
        }
    }
    private function getDBConfigFile($env, $writes, $info){
        //file line starts from 0
        $file       = "config/dBDetails.php";
        $contents   = "";
        $started    = false;
        $ended      = false;
        $fileHandler = fopen($file, "r");
        $sets = [
            "pass" => [
                "status"=>false,
                "set"=>in_array("pass", $writes)
            ],
            "user" => [
                "status"=>false,
                "set"=>in_array("user", $writes)
            ],
            "charset" => [
                "status"=>false,
                "set"=>in_array("charset", $writes)
            ],
            "database" => [
                "status"=>false,
                "set"=>in_array("database", $writes)
            ],
        ];
        $updates = [];
        $lastValues = [
            "database" => $info["database"]["old"],
            "user"=>$info["user"]["old"],
            "pass"=>$info["pass"]["old"],
            "charset"=>$info["charset"]["old"]
        ];

        $oldPassword = strlen($info["pass"]["old"])>0?$info["pass"]["old"]:"''";
        $newPassword = strlen($info["pass"]["old"])>0?$info["pass"]["new"]:"'".$info["pass"]["new"]."'";
        $oldDatabase = strlen($info["database"]["old"])>0?$info["database"]["old"]:"''";
        $newDatabase = strlen($info["database"]["old"])>0?$info["database"]["new"]:"'".$info["database"]["new"]."'";
        $oldCharset  = strlen($info["charset"]["old"])>0?$info["charset"]["old"]:"''";
        $newCharset  = strlen($info["charset"]["old"])>0?$info["charset"]["new"]:"'".$info["charset"]["new"]."'";

        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if(!$started){
                if(substr_count($line,  $env) > 0){
                    $started = true;
                }
            }

            if($started && !$ended){
                if(strpos($line, '$user') != false){//user
                    if($sets["user"]["set"] && !$sets["user"]["status"]){
                        $contents .= str_replace($info["user"]["old"], $info["user"]["new"], $line, $count);
                        if($count > 0){
                            $lastValues["user"] = $info["user"]["new"];
                            $sets["user"]["status"] = true;
                            array_push($updates, "user");
                        } 
                    }else{
                        $contents .= $line;
                    }
                }else if(strpos($line, '$db') != false){//database
                    if($sets["database"]["set"] && !$sets["database"]["status"]){
                        $contents .= str_replace($oldDatabase , $newDatabase , $line, $count);
                        if($count > 0){
                            $lastValues["database"] = $info["database"]["new"];
                            $sets["database"]["status"] = true;
                            array_push($updates, "database");
                        } 
                    }else{
                        $contents .= $line;
                    }   
                }else if(strpos($line, '$pass') != false){//password
                    if($sets["pass"]["set"] && !$sets["pass"]["status"]){
                        $contents .= str_replace($oldPassword, $newPassword, $line, $count);
                        if($count > 0){
                           $lastValues["pass"] = $info["pass"]["new"];
                           $sets["pass"]["status"] = true; 
                           array_push($updates, "pass");
                        } 
                    }else{
                        $contents .= $line;
                    }   
                }else{
                    $contents .= $line;
                }  
                if(substr_count($line,  "break") > 0) $ended = true;
            }else{
                if($sets["charset"]["set"] && !$sets["charset"]["status"]){
                    $contents .= str_replace($oldCharset, $newCharset, $line, $count);
                    if($count > 0){
                        $lastValues["charset"] = $info["charset"]["new"];
                        $sets["charset"]["status"] = true;
                        array_push($updates, "charset");
                    } 
                }else{
                    $contents .= $line;
                } 
            }            
        }
        
        fclose($fileHandler);
        return [
            "updates"   => count($updates),
            "contents"  => $contents,
            "values"    => $lastValues
        ];
    }
    private function writeDatabaseInfo($env, $writes, $info){
       $newDbInfoContent =  $this->getDBConfigFile($env, $writes, $info);
       if($newDbInfoContent["updates"] == count($writes)){ //content updated for writing
            //write
            $dbConfigFile = "config/dBDetails.php";
            if($this->executeWrite($dbConfigFile, $newDbInfoContent["contents"])){
                return[
                    "status" => true,
                    "values" => $newDbInfoContent["values"]
                ];
            }else{
                return[
                    "status" => false,
                    "values" => $newDbInfoContent["values"]
                ];
            };
       }else{
           return[
               "status" => false,
               "values" => $newDbInfoContent["values"]
           ];
       }
    }
    private function validateDBPassword($dbInfo, $user=null, $password=null){
        try {
            $password = $password != null?$password:$dbInfo["pass"];
            $user = $user != null?$user:$dbInfo["user"];

            $xdsn = "mysql:host={$dbInfo["host"]}";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];
            $xPdo = new PDO($xdsn, $user, $password, $opt);	
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    private function pcd(){
        $dbFile = require("config/dbDetails.php");
        $this->info(["Your current database is: ",$dbFile["db"],""]);
    }
    // Database ends

    //Commands
    private function startServer(){
        //get port
        $serverCommand = $this->getAction($this->argv[1]);
        if(array_key_exists("action", $serverCommand)){
            $port = explode("@", $serverCommand["action"]);
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
            $this->write("ZLight App ".$this->appConfig->appName." started at 127.0.0.1:".$port[1]."\n", "green", "black");
            $this->write("Press ".$this->color("Ctrl + C", "yellow", "black"). " to shutdown server\n", "green", "black");
            shell_exec($command);
        }
    }
    private function build($action){
        //Action name must only be alphabets
        
        switch (strtolower($action)) {
            case 'schema':
                //track setup
                $this->setupCheck();
                if(!isset($this->argv[2])){// Please specify the build type
                    $this->error(["Please specify the build type: '-new | -n', '-tracked | -t' or  'schemaFileName'","",""]);
                }
                
                //build specific schema, init or new
                $arg2 = strtolower($this->argv[2]);
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
                    if(!$this->validate("alphaNum", $this->argv[2])){
                        $this->error(["Schema name must be alpha numeric only, the name:", $this->argv[2], " is not all alpha numeric"]);
                    }
                    
                    $buildSchema = $this->buildSchema($this->argv[2]);

                    if($buildSchema["status"]){//Schema exist
                        if($buildSchema["code"] == 1){//Built successfully
                            $this->success(["The schema: '{$this->argv[2]}' has been built successfully", "",""], false);
                        }
                    }else{//No build
                        if($buildSchema["code"] == 4){
                            $this->warning(["The schema file: '{$this->argv[2]}.sql' is empty, found nothing to build", "",""]);
                        }else if($buildSchema["code"] == 2){
                            $this->error(["The schema file : '{$this->argv[2]}' is not found. Create one using the: ","create:schema"," command"]);
                        }else if($buildSchema["code"] == 3){//Already built
                            $this->warning(["The schema: '{$this->argv[2]}' has been built already. Use the command: ", "reset:schema"," to reset the schema if you want to build again"]);
                        }
                    }
                }else{
                    $this->autoBuildSchema($mode);
                }                    
                
                break;
        }
    }
    private function reset($action){
        switch (strtolower($action)) {
            case 'schema':  
                //track setup
                $this->setupCheck();

                $rebuild=false; 
                $schemaFile = "";
                $auto=true;
                if(isset($this->argv[2])){
                    
                    if(strtolower($this->argv[2]) != "-r"){// target file given to be reset but not to build
                        //Validate schema name
            
                        if(!$this->validate("alphaNum", $this->argv[2])){
                            $this->error(["Schema name must be alpha numeric only, the name:", $this->argv[2], " is not all alpha numeric"]);
                        }

                        $schemaFile = $this->argv[2];
                        $auto = false;
                        $rebuild = false;
                    }else if(strtolower($this->argv[2]) == "-r"){//auto rebuilding enable
                        if(isset($this->argv[3])){ //has specific schema for reseting and building
                            if(!$this->validate("alphaNum", $this->argv[3])){
                                $this->error(["Schema name must be alpha numeric only, the name:", $this->argv[3], " is not all alpha numeric"]);
                            }
                            $auto = false;
                            $schemaFile = $this->argv[3];
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
    private function untrack($action){
        switch (strtolower($action)) {
            case 'schema': 
                //track setup
                $this->setupCheck();

                if(!isset($this->argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be untracked", "", ""]);
                } 

                if(!$this->validate("alphaNum", $this->argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $this->argv[2], " is not all alpha numeric"]);
                }

                $fileCheck = $this->getSchema($this->argv[2]);
                
                $answered = false;
                while(!$answered){
                    if($fileCheck["status"]){ //file exist
                        $prompt = $this->color(" The schema file: '{$this->argv[2]}.sql' exist, are you sure you want to untrack? Y or N ", "blue", "yellow");
                    }else{
                        $prompt = $this->color(" Are you sure you want to untrack the schema: '{$this->argv[2]}'? Y or N ", "blue", "yellow");
                    }
                    
                    $input =  $this->readLine($prompt);  
                    if($input != "n" && $input != "y"){
                        echo "Please press 'Y' for yes and 'N' for no\n";
                        continue;
                    } 

                    if(strtolower($input) == "y"){
                        $untrackSchema = $this->untrackSchema($this->argv[2]);
                        if($untrackSchema["status"] && $untrackSchema["rowCount"] > 0){//
                            $this->success(["The schema: '{$this->argv[2]}' has been untracked successfully", "",""], false);
                        }else{
                            $this->warning(["The schema: '{$this->argv[2]}' was never tracked", "",""]);
                        }
                        $answered = true;
                    }else{
                        $answered = true;
                    }
                }
                break;
        }     
    }
    private function track($action){
        switch (strtolower($action)) {
            case 'schema': 
                //track setup
                $this->setupCheck();

                if(!isset($this->argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be tracked", "", ""]);
                } 

                if(!$this->validate("alphaNum", $this->argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $this->argv[2], " is not all alpha numeric"]);
                }
                
                $checkSchema = $this->getSchema($this->argv[2]);
                if($checkSchema["status"]){//schema exist, check if tracked before tracking
                    $isTracked = $this->trackStatus($this->argv[2]);
                    if(!$isTracked){
                        $trackSchema = $this->trackSchema($this->argv[2], $checkSchema["tableName"]);
                        if($trackSchema["status"] && $trackSchema["rowCount"] > 0){//tracked successfuly
                            $this->success(["The schema: '{$this->argv[2]}' has been tracked successfully. Run the ", "build:schema {$this->argv[2]}"," to build the schema"], false);
                        }else{
                            $this->error(["Error tracking the schema: '{$this->argv[2]}'", "",""], false);
                        }
                    }else{
                        $this->warning(["The schema: '{$this->argv[2]}' is tracked already", "",""], false);
                    }                    
                }else{//file not exist
                    $this->error(["The schema file: '{$this->argv[2]}.sql' does not exist", "",""], false);
                }
                break;
        }
    }
    private function delete($action){
        switch (strtolower($action)) {
            case 'schema':
                //track setup
                $this->setupCheck();

                if(!isset($this->argv[2])){
                    $this->error(["The Schema name must be supplied, please supply a schema name to be deleted", "", ""]);
                } 

                if(!$this->validate("alphaNum", $this->argv[2])){
                    $this->error(["Schema name must be alpha numeric only, the name: ", $this->argv[2], " is not all alpha numeric"]);
                }

                $checkSchema = $this->getSchema($this->argv[2]);
                if($checkSchema["status"]){//schema exist, delete
                    $answered = false;
                    while(!$answered){
                        $prompt = $this->color(" The schema file: '{$this->argv[2]}.sql' exist, are you sure you want to delete? Y or N ", "blue", "yellow");
                        $input =  $this->readLine($prompt);  
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        if(strtolower($input) == "y"){
                            if($this->deleteSchema($this->argv[2], $checkSchema["tableName"])){
                                $this->success(["The schema: '{$this->argv[2]}' has been deleted successfully", "",""], false); 
                                $answered = true;
                            }                        
                        }else{
                            $answered = true;
                        }
                    }
                }else{
                    $this->error(["The schema file: '{$this->argv[2]}.sql' does not exist ", "", ""]);
                }
            break;
        }
    }
    private function create($action){
        //check for new object name
        if(!isset($this->argv[2])){
            $this->error(["Please specify the ", $action, " name"]);
        }

        if($action != "database"){
            //name must only be alphabets
            if(!$this->validate("alpha", $this->argv[2])){
                $label = strtolower($action) == "schema"?"schema":$this->argv[2];
                $this->error(["The ".ucwords($label)." name must be alphabets only, the name: ", $this->argv[2], " is not all alphabets"]);
            }
        }
       
        switch (strtolower($action)) {
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
                $this->buildTemplate("schema");
                break;
            case 'database':
                $this->createDatabase();
                break;
            default:
                # code...
                break;
        }
    }
    private function initialize($action){
        switch (strtolower($action)) {
            case 'database': 
                $databaseExist  = $this->databaseExist($this->dbInfo["db"]);
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
                $setState   = false;
                $env        = "";
                if(!$databaseExist){//Not exist
                    //Environment
                    $environments = ["dev", "test", "production"];  
                    
           
                    $read = false;
                    while(!$read){
                        $prompt = $this->color("Please select the environment to be initialized:\n\t1.Development\n\t2.Testing\n\t3.Production\n\nYour current environment is: '".ENVIRONMENT, "green", "black");
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
                            array_push($writes, "user");
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
                                //Ask for new database user
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
                                array_push($writes, "database");
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
                                    array_push($writes,"pass");
                                    $read = true;
                                }
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
                                    if(!$this->validateDBPassword($this->dbInfo["user"], $input2)){
                                        $this->error(["The password '{$input2}' is incorrect for the user '{$this->dbInfo["user"]}'. Try again", "", ""], false);
                                        continue;
                                    };

                                    $parsedDbInfo["pass"]["new"] = $input2;
                                    array_push($writes,"pass");
                                    $subRead = true;
                                }
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
                                array_push($writes, "charset");
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
                                    array_push($writes,"charset");
                                    $subRead = true;
                                }
                            }
                            $read = true;
                        }
                    }
                   
                    $totalWrites = count($writes);
                    if($totalWrites > 0){
                        $write = $this->writeDatabaseInfo($env, $writes, $parsedDbInfo);
                        if($write["status"]) $setState = true;
                    }

                    $db = $setState? $write["values"]["database"]:$this->dbInfo["db"];
                    $user = $setState? $write["values"]["user"]:$this->dbInfo["user"];
                    $pass = $setState? $write["values"]["pass"]:$this->dbInfo["pass"];
                    $charset = $setState? $write["values"]["charset"]:$this->dbInfo["charset"];
                    
                
                    $this->appConfig->xDB->run("CREATE DATABASE IF NOT EXISTS `{$write["values"]["database"]}`");
                    
                    

                    $msg = "\nDatabase initiated successfuly. \n";                      
                    $msg .= "\tDatabase                 = {$db}\n";
                    $msg .= "\tDatabase username        = {$user}\n";
                    $msg .= "\tDatabase password        = {$pass}\n";
                    $msg .= "\tDatabase character set   = {$charset}\n";
                    
                    echo $msg;
                    $this->setDbInit();                
                }else{//Already initialized
                    $this->warning(["Database has been initialized and set to '{$this->dbInfo["db"]}' already. You can use the ", "create:database ", "command to create another database, if needed"]);
                }
            break;
        }
    }
    private function use($action){
        switch (strtolower($action)) {
            case 'database': 
                if(!isset($this->argv[2])){
                    $this->error(["The Database name must be supplied, please supply the name of the database name to use", "", ""]);
                } 
                $name = $this->argv[2];
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
                    $parsedDbInfo["database"]["new"] = $name;
                    $write = $this->writeDatabaseInfo(ENVIRONMENT, ["database"], $parsedDbInfo);
                    if($write["status"]){
                        $this->success(["The database '{$name}' has been set for use ","",""]);
                        $answered =true;
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
                                $write = $this->writeDatabaseInfo(ENVIRONMENT, ["database"], $parsedDbInfo);
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
    private function current($action){
        switch (strtolower($action)) {
            case 'database': 
                $this->pcd();
            break;
        }
    }
    private function export($action){
        switch (strtolower($action)) {
            case 'data': 
                if(!isset($this->argv[2])){
                    $this->error(["The name of the table to be exported must be supplied, please supply the name of the table.", "", ""]);
                } 
                $name = $this->argv[2];
                if(!$this->validate("name", $name)){
                    $this->error(["The supplied table name '{$name}' is invalid. Please specify a valid name", "", ""]);
                }

                //check if table exist

                $exist = $this->appConfig->db->tableExist($name);
                if($exist["rowCount"] > 0){//export
                    $exportData = $this->exportData($name);
                    $file = $this->appConfig->dataDir."/".$name.$this->appConfig->dataFileSuffix.".zld";
                    if($exportData["status"]){
                        $this->success([$exportData["total"]."/".$exportData["total"] . pluralizer(" record", "s:-1",$exportData["total"])." ".pluralizer("has", "have", $exportData["total"])." been exported to the data file ", $file, ""]);
                    }else{
                        $this->warning(["The table '{$name}' is empty, no data to export", "", ""]);
                    }
                }else{//no table found
                    $this->error(["The table ", $name, " does not exist"]);
                }

            break;
        }
    }
    private function import($action){
        switch (strtolower($action)) {
            case 'data': 
                if(!isset($this->argv[2])){
                    $this->error(["The name of the table to be filled with data must be supplied, please supply the name of the table.", "", ""]);
                } 
                $name = $this->argv[2];
                if(!$this->validate("name", $name)){
                    $this->error(["The supplied table name '{$name}' is invalid. Please specify a valid name", "", ""]);
                }

                //Check if datafile for table exist
                $dataFile = $this->appConfig->dataDir."/".$name.$this->appConfig->dataFileSuffix.".zld";
                if(!file_exists($dataFile)){
                    $this->error(["Data file for the table ", $name, " not found"]);
                }
                
                //check if table exist
                $tableExist = $this->appConfig->db->tableExist($name);
                

                if($tableExist["rowCount"] > 0){//import
                    $importData = $this->importData($name);

                    // if($exportData["status"]){
                    //     $this->success([$exportData["total"]."/".$exportData["total"] . pluralizer(" record", "s:-1",$exportData["total"])." ".pluralizer("has", "been", $exportData["total"])." exported to the data file ", $this->appConfig->dataDir."/".$name.$this->appConfig->dataFileSuffix, ""]);
                    // }else{
                    //     $this->warning(["The table '{$name}' is empty, no data to export", "", ""]);
                    // }
                }else{//no table found
                    $this->error(["The table ", $name, " does not exist"]);
                }

            break;
        }
    }
}
?>