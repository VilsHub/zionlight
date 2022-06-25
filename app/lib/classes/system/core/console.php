<?php
require_once("./app/lib/classes/system/helpers/CLIColors.php");
class Console extends CLIColors{
    private $version  = "1.0.0.beta";
    private $commands =[
        "create" => [
            "middleware",
            "controller",
            "model",
            "queriesbank",
            "service",
            "schema",
            "db"
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
            "db"
        ],
        "use" => [
            "db"
        ],
        "current" => [
            "db"
        ],
        "export" =>[
            "data"
        ],
        "import" =>[
            "data"
        ]
    ];

    private $argv;
    function __construct($argv, $argc, $app){
        parent::__construct();
        $app->boot();
        $this->app      = $app;
        $this->configs  = $app->config;
        $this->argv     = $argv;
        $this->dbInfo   = [
            "db"            => env("DB_DATABASE"),
            "isDBApp"       => env("DB_APP"),
            "user"          => env("DB_USER"),
            "host"          => env("DB_HOST"),
            "pass"          => env("DB_PASSWORD"),
            "charset"       => env("DB_CHARSET")
        ]; 

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
        echo "Here are the things you can do:";
        $list = "\n".$this->color(" CREATE", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Create controller", "yellow", "black")."\t".$this->color("create:controller", "green", "black")." controllerName";
        $list .= "\n\t".$this->color(" - Create model", "yellow", "black")." \t".$this->color("create:model", "green", "black")." ModelName";
        $list .= "\n\t".$this->color(" - Create queries bank", "yellow", "black")." \t".$this->color("create:queriesBank", "green", "black")." queriesbankName";
        $list .= "\n\t".$this->color(" - Create middleware", "yellow", "black")." \t".$this->color("create:middleware", "green", "black")." middlewareName";
        $list .= "\n\t".$this->color(" - Create schema", "yellow", "black")." \t".$this->color("create:schema", "green", "black")." schemaName";
        $list .= "\n\t".$this->color(" - Create database", "yellow", "black")." \t".$this->color("create:db", "green", "black")." databaseName";
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
        $list .= "\n\t".$this->color(" - View current DB", "yellow", "black")." \t".$this->color("current:db", "green", "black");

        echo $list;
        echo "\n\n";
    }
    private function commandManager($command){
        $CommandInfo = $this->getAction($command);
        $exec = $CommandInfo["command"];
        $this->validateCommand($exec);
        
        switch (strtolower($exec)){
            case 'create':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->create($object);
                break;
            case 'shine':
                $this->startServer();
                break;
            case 'build':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->build($object);
                break;
            case 'reset':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->reset($object);
                break;
            case 'untrack':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->untrack($object);
                break;
            case 'track':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->track($object);
                break;
            case 'delete':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->delete($object);
                break;
            case 'initialize':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->initialize($object);
                break;
            case 'use':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->use($object);
                break;
            case 'current':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->current($object);
                break;
            case 'export':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->export($object);
                break;
            case 'import':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->import($object);
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
            $build["object"] = $info[1];
        }else{
            $build["object"] = null;
        }

        return $build;
    }
    private function validateCommand($command){
        $command = strtolower($command);
        if (!array_key_exists($command, $this->commands)){
            $this->error(["The command: ", $command, " is not supported"]);
        }
    }
    private function validateCommandActionObject($command, $object){
        $object = strtolower($object);
        $totalCommandAction = count($this->commands[$command]);
        if($object == null){
            $this->error(["No ".strtoupper($command)." command object supplied. You have not specified any object. The supported ".pluralizer("object", "s:-1", $totalCommandAction)." for ".pluralizer("this", "ese:2", $totalCommandAction)." ".pluralizer("command", "s:-1", $totalCommandAction)." ".pluralizer("is", "are", $totalCommandAction).": ". implode(", ", $this->commands[$command] ).". Example ", $command.":".$this->commands[$command][0], ""]);
        }else if(!in_array($object, $this->commands[$command])){
            $this->error(["The ".strtoupper($command)." command cannot operate on the specified object:", $object, " It is not supported"]);
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
    private function executeWrite($fileName, $content){
        $fileHandler = fopen($fileName, "w");
        if (fwrite($fileHandler, $content) > 0){
            return true;
        }else{
            return false;
        };
    }
    private function buildTemplate($type){
        $name = ucwords($this->argv[2]);
        switch ($type) {
            case 'controller':
                $dir = $this->configs->controllersDir;
               
                $controllerContent =  $this->getTemplate("class", "controller", $name, [9]);

                //write to new controller file
                $newControllerFile = $dir."/".$name.".php";

                if($this->executeWrite($newControllerFile, $controllerContent)){
                    $this->success(["The controller: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'model':
                $dir = $this->configs->modelsDir;
                $modelContent =  $this->getTemplate("class", "model", $name.$this->configs->modelFileSuffix, [7]);

                //write to new model file
                $newModelFile = $dir."/".$name.$this->configs->modelFileSuffix.".php";
                if($this->executeWrite($newModelFile, $modelContent)){
                    $this->success(["The Model: ",$name.$this->configs->modelFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'middleware':
                $dir = $this->configs->middlewaresDir;
                $middlewareContent =  $this->getTemplate("class", "middleware", $name, [6]);

                //write to new middleware file
                $newMiddlewareFile = $dir."/".$name.".php";
                if($this->executeWrite($newMiddlewareFile, $middlewareContent)){
                    $this->success(["The Middleware: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'queriesbank':
                $dir = $this->configs->queriesBankDir;
                $queryBankContent =  $this->getTemplate("class", "queriesbank", $name.$this->configs->queryFileSuffix, [6]);

                //write to new middleware file
                $newQuerybankFile = $dir."/".$name.$this->configs->queryFileSuffix.".php";
                if($this->executeWrite($newQuerybankFile, $queryBankContent)){
                    $this->success(["The QueryBank: ",$name.$this->configs->queryFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'service':
                $dir = $this->configs->servicesDir;
                $serviceContent =  $this->getTemplate("class", "service", $name.$this->configs->serviceFileSuffix, [10]);

                //write to new service file
                $newServiceFile = $dir."/".$name.$this->configs->serviceFileSuffix.".php";
                if($this->executeWrite($newServiceFile, $serviceContent)){
                    $this->success(["The Service: ", $name.$this->configs->serviceFileSuffix, " has been created successfully in the directory: ".$dir]);
                }

                break;
            case 'schema':
                $this->setupCheck();
                $dir = $this->configs->schemaDir;
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
                        $this->error(["The supplied object name '{$this->argv[4]}' is invalid. Please specify a valid name", "", ""]);
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
    private function getTemplate($templateType, $templateName, $placeholderName=null, $placeholderLine){
        $file = "";
        $contents = "";
        $n = 0;
        $dir = $this->configs->appRootDir."/app/assets/templates/";
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
    // CLI ends


    // Data Methods
    private function insertData($schemaName, $tableName, $insertQuery){
        //check if empty
        $reset = $this->resetSchema($schemaName, $tableName, true);
        if($reset["code"] == "r2"){ //reset and built successfully
            //insert data
            $this->configs->db->disableForeignKeyCheck();
            $run = $this->configs->db->run($insertQuery);
            $this->configs->db->enableForeignKeyCheck();
 
            return [
                "status" => $run["status"],
                "totalInserted" => $run["rowCount"]
            ];
        }else{
            return false;
        }
    }
    private function parseCellData($cell, $type){
        $parsedCellData = null;
        if(strlen($cell) == 0){ //null
            $parsedCellData = "NULL";
        }else{ //not null
            if($type == "integer"){
                $parsedCellData = $cell;
            }else if ($type == "string"){
                $cell = strpos($cell, "'") >= 0 ? str_replace("'", "\'", $cell):$cell;
                $parsedCellData = "'".$cell."'";
            }
        }
        return $parsedCellData;
    }
    private function exportData($tableName){
        $dataFile           = $this->configs->dataDir."/".$tableName.$this->configs->dataFileSuffix.".zld.sql";
        $dataEngineVersion  = $this->configs->db->link->query('select version()')->fetchColumn();
        $tableConfig        = [];
        $data               = "";
        
        //build table columns names
        $sql                = "DESCRIBE `{$tableName}`";
        $run                = $this->configs->db->run($sql);
        $totalColumns       = $run["rowCount"];
       
        $data               .= "-- Zion Light SQL Dump\n";
        $data               .= "-- App Name         : {$this->configs->appName}\n";
        $data               .= "-- Generation Time  : ".date("M d, Y \a\\t h:i A")."\n"; // Jun 20, 2022 at 05:28 PM";
        $data               .= "-- Data Engine      : ".env("DB_ENGINE")." version {$dataEngineVersion}"."\n";
        $data               .= "-- Database Name    : ".env("DB_DATABASE")."\n";
        $data               .= "-- Table Name       : {$tableName}"."\n\n";
        $data               .= "--\n";
        $data               .= "-- Dumping data for table `{$tableName}`\n";
        $data               .= "--\n\n";
      
        $data               .= "INSERT INTO `{$tableName}` (";

        foreach($run["data"] as $key => $tableRow){ 
            if($totalColumns == $key+1){
                $data .= "`{$tableRow["Field"]}`) VALUES\n";
            }else{
                $data .= "`{$tableRow["Field"]}`, ";
            }

            //set table config        

            if (strpos($tableRow["Type"], "int") > -1){
                $tableConfig[$tableRow["Field"]] = "integer";
            }else if (strpos($tableRow["Type"], "float") > -1){
                $tableConfig[$tableRow["Field"]] = "integer";
            }else if(strpos($tableRow["Type"], "varchar") > -1){
                $tableConfig[$tableRow["Field"]] = "string";
            }else{
                $tableConfig[$tableRow["Field"]] = "string";
            }
        }

        //build rows
        $sql            = "SELECT * FROM `{$tableName}`";
        $run            = $this->configs->db->run($sql);
        $totalRecords   = $run["rowCount"];
        
        if($totalRecords > 0){
            $rows=0;
            
            foreach($run["data"] as $key => $tableRow){

                $n=0;
                $rows++;

                foreach($tableRow as $key => $cell){
                    if($totalColumns  == $n+1){//last cell of rows
                        if($rows == $totalRecords){ //last cell of last record
                            $data .= $this->parseCellData($cell, $tableConfig[$key]).");\n";
                        }else{//last cell of other records
                            $data .= $this->parseCellData($cell, $tableConfig[$key])."),\n";
                        }
                    }else if($n==0){
                        $data .= "(".$this->parseCellData($cell, $tableConfig[$key]).", ";
                    }else{
                        $data .= $this->parseCellData($cell, $tableConfig[$key]).", ";
                    }   
                    $n++;
                }
            }

            $this->executeWrite($dataFile, $data);

            return [
                "status"=> true,
                "total" => $totalRecords
            ];
        }else{
            return [
                "status"=> false,
                "total" => 0
            ];
        }

    }
    private function importData($dataFile){
        //build values
        $data   = $this->readDumpContent($dataFile);
       
        $insertQuery    = $data["schema"];
        $tableName      = $data["tableName"];
        $totalInserted  = 0;

        //check if table exist
        $tableExist = $this->configs->db->tableExist($tableName);
       
        if ($tableExist){
            //get schema name
            $schemaName = $this->getTableSchemaName($tableName)["schema_name"];
           
            $answered = false;

            while (!$answered) { 
                $prompt = $this->color("\n You are about to empty the table: '$tableName' and fill it with new data. Do you proceed? Y or N ", "blue", "yellow");
                $input =  $this->readLine($prompt);  
                if($input != "n" && $input != "y"){
                    echo "Please press 'Y' for yes and 'N' for no\n";
                    continue;
                } 

                if(strtolower($input) == "y"){
                    $insertData = $this->insertData($schemaName, $tableName, $insertQuery);
                    if($insertData["status"]){//imported
                        $totalInserted  = $insertData["totalInserted"];
                        $status = "c0";
                    }else{//could not import data
                        $status = "c2";
                    } 
                }

                $answered = true;
            }
        }else{
            $status = "c1";
        }

        return [
            "code" => $status,
            "tableName" => $tableName,
            "totalInserted" => $totalInserted
        ];
    }
    

    // Schema starts
    private function getSchema($name){
        $schemaFile = $this->configs->schemaDir."/".$name.".sql";
        if(file_exists($schemaFile)){
            $data = $this->readSchemaContent($schemaFile);
            $data["status"] = true;
        }else{
            $data = [
                "schema"    => null,
                "tableName" => null,
                "status"    => false
            ];
        }
        
        return [
            "data"      => $data["schema"],
            "status"    => $data["status"],
            "tableName" => $data["tableName"]
        ];
    }
    private function readDumpContent($schemaFile){
        $fileHandler = fopen($schemaFile, "r");
        $content = "";
        $tableName = "";
        $setTableName = false;
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            $comment = strpos($line, "--") > -1 ?$line[0].$line[1]:null;

            if($comment == "--"){
                if(!$setTableName){
                    if(strpos($line, "Table Name") > -1){//located file name
                        $tableName = trim(explode(":", $line)[1]);
                        $setTableName = true;
                    }
                }else{
                    continue;
                }
            }            
            $content .= $line;   
        }
        
        fclose($fileHandler);

        return [
            "tableName" => $tableName,
            "schema"    => $content
        ];
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
        $schemaFile = $this->configs->schemaDir."/".$name.".sql";
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
            `table_name` VARCHAR(50) NOT NULL,
            `build` ENUM ('0', '1') DEFAULT '0'
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        return $run = $this->configs->db->run($sql);
    }
    private function buildStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '1'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function resetStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '0'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackTableName($name){
        $sql = "SELECT `table_name` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $run = $this->configs->db->getRecord($sql);
        return $run["table_name"];
    }
    private function trackedFiles(){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track`";
        $run = $this->configs->db->run($sql);
        $names = [];

        foreach ($run["data"] as $key => $value) {
            array_push($names, $value["schema_name"].".sql");
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
        $exist = $this->configs->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '1' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '1', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->configs->db->run($sql);
    }
    private function unlogSchema($name, $tableName){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $exist = $this->configs->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '0', `table_name` = '{$tableName}' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->configs->db->run($sql);
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
                        $this->configs->db->disableForeignKeyCheck();
                        $run = $this->configs->db->run($schemaData["data"]);
                        $this->configs->db->enableForeignKeyCheck();
                        
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
    private function writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, $points){
        $schemaContent =  $this->getTemplate("schema", $schemaTemplateName, $tableName, $points);
        if($this->executeWrite($newSchemaFile, $schemaContent)){
            //track schema file
            return $this->trackCreatedSchema($schemaName, $tableName);
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
    private function dropTable($tableName){
        $this->configs->db->disableForeignKeyCheck();
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        $run = $this->configs->db->run($sql);
        $this->configs->db->enableForeignKeyCheck();
        return $run;
    }
    private function getTableSchemaName($tableName){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track` WHERE `table_name` = '{$tableName}'";
        return $this->configs->db->getRecord($sql);
    }
    private function resetSchema($name, $tableName=null, $rebuild=true){
        //Check track State
        $trackTableName = $this->trackTableName($name);
        
        $replace = ($tableName != null)? true: false;
       
        $targetTable = "";
       
        if(strlen($trackTableName)){
            //reset

            try {
                // drop old
                if($replace) {
                    $this->dropTable($trackTableName); 
                    $run = $this->dropTable($tableName);
                    $targetTable = $tableName;
                }else{
                    $run =$this->dropTable($trackTableName); 
                    $targetTable = $trackTableName;
                }
                
                if($run["status"]){
                    //reset in tracker
                    $this->unlogSchema($name, $targetTable);
                    
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
        $schemas = array_slice(scandir($this->configs->schemaDir), 2);
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
            $input  =  $this->readLine($prompt);
            
            if($input != "n" && $input != "y"){
                echo "Please press 'Y' for yes and 'N' for no\n";
                continue;
            } 

            if(strtolower($input) == "y"){
                $schemaData         = $this->getSchemaFiles();
                $totalTracked       = count($schemaData["trackedFiles"]);
                $totalSchema        = count($schemaData["schemaFiles"]);
                $missingSchema      = array_diff($schemaData["trackedFiles"], $schemaData["schemaFiles"]);
                $untrackedSchema    = array_diff($schemaData["schemaFiles"], $schemaData["trackedFiles"]);

                if($totalTracked > 0){
                    foreach ($schemaData["trackedFiles"] as $key => $value) {
                        $name = str_replace(".sql", "", $value);

                        if(!$this->resetStatus($name)){//has not been reset

                            $state =  $this->resetSchema($name, null, $rebuild);

                            if($state["status"]){
                                if($state["code"] == "r2"){
                                    $this->success(["The schema: '{$name}' has been reset and built successfully", "",""], false);
                                }else if($state["code"] == "r1"){
                                    $this->success(["The schema: '{$name}' has been reset successfully without rebuilding","",""], false);
                                }
                            }
                        }else{//reset already
                            $this->warning(["The schema : '{$name}' has already been reset","",""]);
                        }
                    }
                }else{
                    $this->warning(["No tracked schema found","",""]); 
                }
               

                $totalMissing   = count($missingSchema);
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
        return $this->configs->db->run($sql);
    }
    private function trackSchema($name, $tableName){
        $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        return $this->configs->db->run($sql);
    }
    private function setupCheck(){
        if ($this->app->pingDatabaseServer()["status"]){
            $this->databaseCheck($this->dbInfo["db"]);
            $trackState = $this->configs->db->tableExist("zlight_schema_track");
            if($trackState["rowCount"] == 0){// create track table
                $this->createTrackTable();
            }
        }
    }
    // Schema ends


    // Database starts
    private function databaseCheck($name){
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
    private function databaseExist($name){
        if(strlen($name) == 0){
            return false;
        }else{
            $sql = "SHOW DATABASES LIKE '{$name}'";
            $run = $this->configs->pdo->query($sql);
            return $run->rowCount() > 0;
        }
    }
    private function executeCreateDb($name){
        $sql = "CREATE DATABASE `{$name}`";
        $run = $this->configs->pdo->query($sql);
        return $run->rowCount() > 0;
    }
    private function createDatabase(){
        $name = ucwords($this->argv[2]);
  
        if(!$this->validate("name", $name)){
            $this->error(["The supplied database name '{$name}' is invalid. Please specify a valid name", "", ""]);
        }

        $exist = $this->databaseExist($name);
  
        if(!$exist){ // create it
            
            if(env("DB_DATABASE") == $name){ //Already set
                $this->warning(["The database: ", "'{$name}'", "is already set"]);
            }else{ //new database name
                $createDb = $this->executeCreateDb($name);
                if($createDb){
                    $this->success(["The database:", $name, " has been created successfully"]);
                }else{
                    $this->error(["Error encountered while creating the database: ", "'{$name}'", ""]);
                }
            }
        }else{
            $this->error(["The database ", "'{$name}'", "already exist"]);
        }
    }
    private function getDBConfigFile($writes, $info){
        //file line starts from 0
        $file       = $this->configs->envFile;
        $contents   = "";
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
   
            if(strpos($line, 'DB_USER') > -1){//user
                if($sets["user"]["set"] && !$sets["user"]["status"]){
                    $contents .= str_replace($info["user"]["old"], $info["user"]["new"], $line, $count);
                    if($count > 0){
                        $lastValues["user"] = $info["user"]["new"];
                        $sets["user"]["status"] = true;
                        array_push($updates, "user");
                        addEnv("DB_USER", $info["user"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }
            }else if(strpos($line, 'DB_DATABASE') > -1){//database
                if($sets["database"]["set"] && !$sets["database"]["status"]){
                    $contents .= str_replace($oldDatabase , $newDatabase , $line, $count);
                    if($count > 0){
                        $lastValues["database"] = $info["database"]["new"];
                        $sets["database"]["status"] = true;
                        array_push($updates, "database");
                        addEnv("DB_DATABASE", $info["database"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }   
            }else if(strpos($line, 'DB_PASSWORD') > -1){//password
                if($sets["pass"]["set"] && !$sets["pass"]["status"]){
                    $contents .= str_replace($oldPassword, $newPassword, $line, $count);
                    if($count > 0){
                        $lastValues["pass"] = $info["pass"]["new"];
                        $sets["pass"]["status"] = true; 
                        array_push($updates, "pass");
                        addEnv("DB_PASSWORD", $info["pass"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }   
            }else if(strpos($line, 'DB_CHARSET') > -1){//Database charater set
                if($sets["charset"]["set"] && !$sets["charset"]["status"]){
                    $contents .= str_replace($oldCharset, $newCharset, $line, $count);
                    if($count > 0){
                        $lastValues["charset"] = $info["charset"]["new"];
                        $sets["charset"]["status"] = true;
                        array_push($updates, "charset");
                        addEnv("DB_CHARSET", $info["charset"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                } 
            }else{
                $contents .= $line;
            }  
        }

        fclose($fileHandler);

        return [
            "updates"   => count($updates),
            "contents"  => $contents,
            "values"    => $lastValues
        ];
    }
    private function writeDatabaseInfo($writes, $info){
       $newDbInfoContent =  $this->getDBConfigFile($writes, $info);
       if($newDbInfoContent["updates"] == count($writes)){ //content updated for writing
            //write

            if($this->executeWrite($this->configs->envFile, $newDbInfoContent["contents"])){
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
    private function validateDBPassword($host, $user, $password){
        try {
            $xdsn = "mysql:host={$host}";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];
            new PDO($xdsn, $user, $password, $opt);	
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    private function pcd(){
        $this->info(["Your current database is: ",env("DB_DATABASE"),""]);
    }
    // Database ends


    //Commands
    private function startServer(){
        //get port
        $serverCommand = $this->getAction($this->argv[1]);

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
    private function build($object){
        //Action name must only be alphabets
        
        switch (strtolower($object)) {
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
    private function reset($object){
        switch (strtolower($object)) {
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
    private function untrack($object){
        switch (strtolower($object)) {
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
    private function track($object){
        switch (strtolower($object)) {
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
                    $isTracked = $this->trackStatus($this->argv[2], $checkSchema["tableName"]);
                    if(!$isTracked){
                        $trackSchema = $this->trackSchema($this->argv[2], $checkSchema["tableName"]);
                        if($trackSchema["status"] && $trackSchema["rowCount"] > 0){//tracked successfuly
                            $this->success(["The schema: '{$this->argv[2]}' has been tracked successfully. Run the command: ", "build:schema {$this->argv[2]}"," to build the schema"], false);
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
    private function delete($object){
        switch (strtolower($object)) {
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
    private function create($object){

        //check for new object name
        if(!isset($this->argv[2])){
            $this->error(["Please specify the ", $object, " name"]);
        }

        if($object != "db"){
            //name must only be alphabets
            if(!$this->validate("alpha", $this->argv[2])){
                $label = strtolower($object) == "schema"?"schema":$this->argv[2];
                $this->error(["The ".ucwords($label)." name must be alphabets only, the name: ", $this->argv[2], " is not all alphabets"]);
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
            default:
                # code...
                break;
        }
    }
    private function initialize($object){              
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
    private function use($object){
        switch (strtolower($object)) {
            case 'db': 
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
                    if($name == env("DB_DATABASE")){
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
    private function current($object){
        switch (strtolower($object)) {
            case 'db': 
                $this->pcd();
            break;
        }
    }
    private function export($object){
        switch (strtolower($object)) {
            case 'data': 
                if(!isset($this->argv[2])){
                    $this->error(["The name of the table to be exported must be supplied, please supply the name of the table.", "", ""]);
                } 
                $name = $this->argv[2];
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
    private function import($object){//from project to database
        switch (strtolower($object)) {
            case 'data': 
                if(!isset($this->argv[2])){
                    $this->error(["The name of the table to be filled with data must be supplied, please supply the name of the table.", "", ""]);
                } 

                $names          = $this->argv[2];

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
                    }

                }

            break;
        }
    }
}
?>