<?php
require_once("./app/lib/classes/system/helpers/CLIColors.php");
class Console extends CLIColors{
    use Commands, Schema, Database, Data;

    private $version  = "1.0.0.beta";
    private $commands =[
        "create" => [
            "middleware",
            "controller",
            "model",
            "queriesbank",
            "service",
            "schema",
            "db",
            "trait"
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
        $list .= "\n\t".$this->color(" - Create service", "yellow", "black")." \t".$this->color("create:sevice", "green", "black")." serviceName";
        $list .= "\n\t".$this->color(" - Create trait", "yellow", "black")." \t".$this->color("create:trait", "green", "black")." traitName";
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
        $list .= "\n\t".$this->color(" - Build schema", "yellow", "black")." \t".$this->color("build:schema", "green", "black")." '-new | -n' OR '-tracked | -t' OR  'schemaFileName'";

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
            case 'trait':
                $dir = $this->configs->traitsDir;
                $TraitContent =  $this->getTemplate("trait", "trait", $name.$this->configs->traitFileSuffix, [7]);

                //write to new trait file
                $newTraitFile = $dir."/".$name.$this->configs->traitFileSuffix.".php";
                if($this->executeWrite($newTraitFile, $TraitContent)){
                    $this->success(["The Trait: ", $name.$this->configs->traitFileSuffix, " has been created successfully in the directory: ".$dir]);
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
        }else{
            $placeholders = ["trait" => "traitName"];
            $dir .= "others/";
            $placeholder = $placeholders[$templateType];
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

}
?>
