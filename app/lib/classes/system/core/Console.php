<?php
require_once("./app/lib/classes/system/helpers/CLIColors.php");
class Console extends CLIColors{
    use Commands, Schema, Database, Data;
    public $app, $configs, $dbInfo, $fileSystem, $argc, $argv;
    private $version  = "1.4.0";
    private $commands =[
        "create" => [
            "middleware",
            "controller",
            "model",
            "queriesbank",
            "service",
            "schema",
            "db",
            "trait",
            "display",
            "seeder"
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
        ],
        "seed" => [
            "db"
        ]
    ];

    function __construct($fileSystemObj, $app){
        
        parent::__construct();
        
        global $argc, $argv;
        $this->app      = $app;
        $this->argc      = $argc;
        $this->argv      = $argv;
        $this->configs  = $app->config;  
        $this->fileSystem = $fileSystemObj;

        $this->app->boot($this->fileSystem);

        $this->dbInfo   = [
            "db"            => getAppEnv("DB_DATABASE"),
            "isDBApp"       => getAppEnv("DB_APP"),
            "user"          => getAppEnv("DB_USER"),
            "host"          => getAppEnv("DB_HOST"),
            "pass"          => getAppEnv("DB_PASSWORD"),
            "charset"       => getAppEnv("DB_CHARSET")
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
        $list .= "\n\t".$this->color(" - Create middleware", "yellow", "black")." \t".$this->color("create:middleware", "green", "black")." middlewareName [options ...]";
        $list .= "\n\t".$this->color(" - Create service", "yellow", "black")." \t".$this->color("create:sevice", "green", "black")." serviceName";
        $list .= "\n\t".$this->color(" - Create trait", "yellow", "black")." \t".$this->color("create:trait", "green", "black")." traitName";
        $list .= "\n\t".$this->color(" - Create schema", "yellow", "black")." \t".$this->color("create:schema", "green", "black")." schemaName";
        $list .= "\n\t".$this->color(" - Create database", "yellow", "black")." \t".$this->color("create:db", "green", "black")." databaseName";
        $list .= "\n\t".$this->color(" - Create display", "yellow", "black")." \t".$this->color("create:display", "green", "black")." displayBlockName [options ...]";
        $list .= "\n\t".$this->color(" - Create seeder", "yellow", "black")." \t".$this->color("create:seeder", "green", "black")." seederName [options ...]";
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

        $list .= "\n".$this->color(" SEED", "light_purple", "black");       
        $list .= "\n\t".$this->color(" - Seed DB", "yellow", "black")." \t\t".$this->color("seed:db", "green", "black")." seederName";

        echo $list;
        echo "\n\n";
    }
    private function commandManager($command){
        $CommandInfo = $this->getAction($command);
        $exec = strtolower($CommandInfo["command"]);
        $this->validateCommand($exec);

        if (!($this->dbInfo["isDBApp"] && $exec == "create" && $CommandInfo["object"] == "db")){// apps command
            $this->app->DBInit("app");
        }else{ //system command
            $this->app->DBInit("system");
        }
       
        switch ($exec){
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
            case 'seed':
                $object = $CommandInfo["object"];
                $this->validateCommandActionObject($exec, $object);
                $this->seed($object);
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
            $this->error(["The ".strtoupper($command)." command cannot operate on the specified object: ", $object, " as it is not supported"]);
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
            case 'integer':
                return preg_match("/[0-9]+/", $value);
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
        global $argv;
        $name = ucwords($argv[2]);
        switch ($type) {
            case 'controller':
                $dir = $this->configs->controllersDir;
               
                $controllerContent =  $this->getTemplate("class", "controller", ["{$name}" =>["lines" => [9], "placeHolder" => "className"]]);
                
                //write to new controller file
                $newControllerFile = $dir."/".$name.".php";

                if($this->executeWrite($newControllerFile, $controllerContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
    
                    if ($output != ""){
                        shell_exec("composer dump-autoload -o -q");
                    }

                    $this->success(["The controller: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                
                break;
            case 'model':
                $dir = $this->configs->modelsDir;

                $fullModelName  = $name.$this->configs->modelFileSuffix;
                $modelContent   =  $this->getTemplate("class", "model", ["{$fullModelName}" =>["lines" => [7], "placeHolder" => "className"]]);

                //write to new model file
                $newModelFile = $dir."/".$name.$this->configs->modelFileSuffix.".php";
                if($this->executeWrite($newModelFile, $modelContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
                    if ($output != ""){
                        shell_exec("composer dump-autoload -o -q");
                    }

                    $this->success(["The Model: ",$name.$this->configs->modelFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'middleware':
                $options = $this->getOptions("", ["from:"]);
                $from=false;
                $dir = $this->configs->middlewaresDir;
                $fromFile="";
                $templateDir = $this->configs->appRootDir."/app/assets/templates";
                $templateName = "middleware";
                $templateType = "class";
               

                if (isset($options["from"]) && isset($options["from"]["data"])){
                    
                    $fromFile=$templateDir."/classes/middlewares/".$options["from"]["data"].".zlt";

                    if (file_exists($fromFile)){
                        $templateName = $options["from"]["data"];
                        $templateType = "pre-madeMiddleware";
                    }else{
                        $this->warning(["No pre-made middleware for '", $options['from']['data'], "' the default middleware content will be generated"]);
                    }
                }

                $middlewareContent =  $this->getTemplate($templateType, $templateName,  ["{$name}" =>["lines" => [6], "placeHolder" => "className"]]);
               
                //write to new middleware file
                $newMiddlewareFile = $dir."/".$name.".php";
               
                if($this->executeWrite($newMiddlewareFile, $middlewareContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
                    if ($output != ""){
                        shell_exec("composer dump-autoload -o -q");
                    }

                    $this->success(["The Middleware: ",$name, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'queriesbank':
                $dir = $this->configs->queriesBankDir;
                $queryBankContent =  $this->getTemplate("class", "queriesbank", $name.$this->configs->queryFileSuffix, [6]);

                //write to new middleware file
                $newQuerybankFile = $dir."/".$name.$this->configs->queryFileSuffix.".php";
                if($this->executeWrite($newQuerybankFile, $queryBankContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
                    if ($output != ""){
                        shell_exec("composer dump-autoload -o -q");
                    }

                    $this->success(["The QueryBank: ",$name.$this->configs->queryFileSuffix, " has been created successfully in the directory: ".$dir]);
                }
                break;
            case 'service':
                $dir = $this->configs->servicesDir;

                $fullServiceName = $name.$this->configs->serviceFileSuffix;
                $serviceContent =  $this->getTemplate("class", "service",  ["{$fullServiceName}" =>["lines" => [10], "placeHolder" => "className"]]);

                //write to new service file
                $newServiceFile = $dir."/".$name.$this->configs->serviceFileSuffix.".php";
                if($this->executeWrite($newServiceFile, $serviceContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
                    if ($output != ""){
                        shell_exec("composer dump-autoload -o -q");
                    }

                    $this->success(["The Service: ", $name.$this->configs->serviceFileSuffix, " has been created successfully in the directory: ".$dir]);
                }

                break;
            case 'trait':
                $dir = $this->configs->traitsDir;

                $traitFullname = $name.$this->configs->traitFileSuffix;
                $TraitContent =  $this->getTemplate("others", "trait", ["{$traitFullname}" =>["lines" => [7], "placeHolder" => "traitName"]]);

                //write to new trait file
                $newTraitFile = $dir."/".$name.$this->configs->traitFileSuffix.".php";
                if($this->executeWrite($newTraitFile, $TraitContent)){

                    //Make newly created class visible to composer
                    $output = shell_exec("composer --version -q");  //Check if composer path exist     
                    if ($output != ""){
                        shell_exec("composer dump-autoload --o -q");
                    }
                    
                    $this->success(["The Trait: ", $name.$this->configs->traitFileSuffix, " has been created successfully in the directory: ".$dir]);
                }

                break;
            case 'schema':
                $this->setupCheck();
                $dir = $this->configs->schemaDir;
                $tableName = "";
                $schemaName="";
                
                //validate template file name
                if(!$this->validate("name", $argv[2])){
                    $this->error(["The schema file name: ".$argv[2]." name must be alphabets only, including '- and _', the name: ", $argv[2], " does not match"]);
                }


                //write to new schema file
                $newSchemaFile = $dir."/".strtolower($argv[2]).".sql";
                $supportedDDS = ["-create.table", "-alter.table", "-rename.table"];
               
                if(!isset($argv[3])){
                    $this->error(["Please specify the table name or definition type. Example: ", "create:schema test sample | create:schema test -create.table sample ",""]);
                }

                $argv[3] = strtolower($argv[3]);
                
                //set type
                if(in_array($argv[3], $supportedDDS)){ //needs schema template
                    $objectType = ucwords(explode(".", $argv[3])[1]);
                    if(!isset($argv[4])){ //Must supply object name
                        $this->error(["No {$objectType} name given. Please specify one", "", ""]);
                    }else{
                        //validate object name
                        if(!$this->validate("name", $argv[4])){
                            $this->error(["The supplied {$objectType} name '{$argv[4]}' is invalid. Please specify a valid name", "", ""]);
                        }
                    }
                    
                    $schemaTemplateName = ltrim($argv[3], "-");
                    $tableName = $argv[4];
                }else{
                    //validate object name
                    if(!$this->validate("name", $argv[3])){
                        $this->error(["The supplied object name '{$argv[4]}' is invalid. Please specify a valid name", "", ""]);
                    } 
                    $schemaTemplateName = "empty.table";
                    $tableName = $argv[3];
                }    
  
                $schemaName = $argv[2];

                //check file if exist
                if(file_exists($newSchemaFile)){
                    $answered =false;
                    while(!$answered){
                        $prompt = $this->color(" The schema file: '", "black", "yellow"). $this->color($argv[2].".sql", "blue", "yellow").  $this->color("' exist, do you want to override? Y or N ", "black", "yellow");
                        $input =  $this->readLine($prompt);
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        if(strtolower($input) == "y"){
                           
                            $write = $this->writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName,  ["{$tableName}" =>["lines" => [1, 2], "placeHolder" => "tableName"]]);
                            if($write){
                                $this->success(["The Schema file: '".$name.".sql' has been created successfully in the directory: '{$dir}'. Complete your schema structure and run: ", "build:schema",""]);
                            }
                            $answered = true;
                        }else{
                            $answered = true;
                        }
                    }
                }else{
                    $write = $this->writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, ["{$tableName}" =>["lines" => [1, 2], "placeHolder" => "tableName"]]);
                    if($write){
                        $this->success(["The Schema file: '".$name.".sql' has been created successfully in the directory: '{$dir}'. Complete your schema structure and run: ", "build:schema",""]);
                    }
                }
                break;
            case 'display':
                //check if display exist
                $displayPath    = $this->configs->displayDir."/".strtolower($name);
                $assetsPath     = $this->configs->publicDir."/assets/css/blocks/".strtolower($name);
                $options        = $this->getOptions("", ["title:", "own-template", "block-name:"]);

                if (is_dir($displayPath)){
                    //Display already exist
                    $this->error(["The diplay: ", $name," already exit. Try a different name"]);
                }else{
                    //get passed options
                    $ownTemplate = array_key_exists("own-template", $options);
                    $ownTitle    = array_key_exists("title", $options);
                    $blockName   = array_key_exists("block-name", $options);


                    //set page title
                    $pageTitle = $ownTitle? $options["title"]["data"]:"Sample page title";

                    //set block name
                    $blockName = $blockName? $options["block-name"]["data"]:strtolower($name);

                    //pare display name
                    $parsedName = strtolower($name);
                    
                    //Create Directories
                    mkdir($displayPath."/".$this->configs->contentsFolder->load."/root", 0777, true);
                    mkdir($displayPath."/".$this->configs->contentsFolder->xhr, 0777, true);
                    mkdir($displayPath."/fragments", 0777, true);
                    mkdir($displayPath."/plugs", 0777, true);

                    if (!is_dir($this->configs->publicDir."/assets/css/blocks/".strtolower($name))) mkdir($this->configs->publicDir."/assets/css/blocks/".strtolower($name), 0777, true);

                    //create new display
                    $indexContent   = $this->getTemplate("display", "index", ["{$name}" => ["lines" => [2], "placeHolder" => "displayName"]]);


                    if ($blockName == $parsedName){ 

                        $nextStepPlaceHolderProp = [
                            "{$blockName}.php" => ["lines" => [39], "placeHolder" => "blockName.php"]
                        ];

                    }else{
                        $nextStepPlaceHolderProp = [
                            "{$blockName}" => ["lines" => [39], "placeHolder" => "blockName"]
                        ];
                    }

                    $nextStepPlaceHolderProp[$parsedName] = ["lines" => [23, 28, 37, 39, 40, 54], "placeHolder" => "displayName"];
                    
                    $nextStep       = $this->getTemplate("others", "nextStep", $nextStepPlaceHolderProp);

                    if($ownTemplate){//don't use zlight template
                        $displayContent     = $this->getTemplate("display", "content_r", [$parsedName => ["lines" => [6, 11, 15], "placeHolder" => "displayName"]]);
                        $footerContent      = $this->getTemplate("display", "footer_r");
                        $headerContent      = $this->getTemplate("display", "header_r");
                        $sampleStyleContent = $this->getTemplate("style", "sample");
                        $headContent        = $this->getTemplate("display", "head", ["{$pageTitle}" =>["lines" => [3], "placeHolder" => "pageTitle"], strtolower($name)  => ["lines" => [4], "placeHolder" => "displayName"]]);    

                    }else{//Use zlight template
                        $displayContent     = $this->getTemplate("display", "content", [$parsedName => ["lines" => [6, 12, 16], "placeHolder" => "displayName"]]);
                        $footerContent      = $this->getTemplate("display", "footer");
                        $headerContent      = $this->getTemplate("display", "header");
                        $contentStyleContent= $this->getTemplate("style", "content");
                        $fontStyleContent   = $this->getTemplate("style", "font");
                        $headerStyleContent = $this->getTemplate("style", "header");
                        $layoutStyleContent = $this->getTemplate("style", "layout");
                        $mediumStyleContent = $this->getTemplate("style", "medium");
                        $largeStyleContent  = $this->getTemplate("style", "large");
                        $headContent        = $this->getTemplate("display", "head", ["{$pageTitle}" =>["lines" => [3], "placeHolder" => "pageTitle"], strtolower($name)  => ["lines" => [4,5,9,10,11], "placeHolder" => "displayName"]]);    
                    }
                   

                    $displayFile        = $displayPath."/".$blockName.".php";
                    $headFile           = $displayPath."/fragments/head.php";
                    $headerFile         = $displayPath."/fragments/header.php";
                    $footerFile         = $displayPath."/fragments/footer.php";
                    $indexFile          = $displayPath."/contents/load/root/index.php";
                    $sampleStyleFile    = $assetsPath."/sample.css";
                    $contentStyleFile   = $assetsPath."/content.css";
                    $fontStyleFile      = $assetsPath."/font.css";
                    $headerStyleFile    = $assetsPath."/header.css";
                    $layoutStyleFile    = $assetsPath."/layout.css";
                    $mediumStyleFile    = $assetsPath."/medium.css";
                    $largeStyleFile     = $assetsPath."/large.css";
                    $nextStepFile       = $displayPath."/nextStep.md";
                    

                    //WriteFiles
                    $writeDisplayFile   = $this->executeWrite($displayFile, $displayContent);

                    $writeHeadFile      = $this->executeWrite($headFile, $headContent);

                    $writeHeaderFile    = $this->executeWrite($headerFile, $headerContent);
    
                    $writeFooterFile    = $this->executeWrite($footerFile, $footerContent);

                    $writeIndexFile     = $this->executeWrite($indexFile, $indexContent);                     
                    
                    if($writeDisplayFile &&  $writeHeadFile && $writeHeaderFile && $writeFooterFile && $writeIndexFile){
                        
                        //copy styles to created block directory
                        if($ownTemplate){
                            $this->executeWrite($sampleStyleFile, $sampleStyleContent);
                        }else{
                            $this->executeWrite($contentStyleFile, $contentStyleContent);
                            $this->executeWrite($fontStyleFile, $fontStyleContent);
                            $this->executeWrite($headerStyleFile, $headerStyleContent);
                            $this->executeWrite($layoutStyleFile, $layoutStyleContent);
                            $this->executeWrite($mediumStyleFile, $mediumStyleContent);
                            $this->executeWrite($largeStyleFile, $largeStyleContent);
                        }
                       
                        $this->executeWrite($nextStepFile, $nextStep);
                        $this->success(["New display block : ",$name, " has been created successfully in the directory: ".$this->configs->displayDir]);

                    }else{
                        $this->error(["Error encountered while creating the : ", $name, " display block"]);
                    }
                }
            case 'seeder':
                $dir = $this->configs->seederDir;

                $seederFullname = $name.".php";
                $options        = $this->getOptions("", ["table-name:", "record-size:"]);

                $newSeederFile = $dir."/".strtolower($seederFullname);

                //Validate record-size option
                if (array_key_exists("record-size", $options)){
                    
                    if (!array_key_exists("data", $options["record-size"])){
                        $this->error(["No record size specified for seeding, pass the value to the option : ", "--record-size=value", " while executing the create:seeder command"]);
                    }else{
                        // check if it is valid
                        if (!$this->validate("integer", $options["record-size"]["data"])){
                            $this->error(["The record size specified for seeding, must be an integer, the supplied value : ", "{$options["record-size"]["data"]}", " is not an integer"]);
                        }
                    }
                }else{
                    $this->error(["No record size specified for seeding, pass the option : ", "--record-size=value", " to the create:seeder command, to specify one"]);
                }

                //Validate table-name option
                if (array_key_exists("table-name", $options)){
                    
                    if (!array_key_exists("data", $options["table-name"])){
                        $this->error(["No table name specified for seeding, pass the value to the option : ", "--table-name=value", " while executing the create:seeder command"]);
                    }else{
                        // check if it is valid
                        if (!$this->validate("alphaNum", $options["table-name"]["data"])){
                            $this->error(["The table name specified for seeding, must be  alpha numberic, the supplied value : ", "{$options["table-name"]["data"]}", " is not alpha numberic"]);
                        }
                    }
                }else{
                    $this->error(["No table name specified for seeding, pass the option : ", "--table-name=value", " to the create:seeder command, to specify one"]);
                }
                
                $buildInstructions = [
                    $options["record-size"]["data"] => [
                        "lines" => [28], 
                        "placeHolder" => "rSize"
                    ], 
                    $options["table-name"]["data"] => [
                        "lines" => [29], 
                        "placeHolder" => "tName"
                    ]
                ];
                
                $seederContent =  $this->getTemplate("others", "seeder",  $buildInstructions);
                
                //check file if exist
                if(file_exists($newSeederFile)){
                    $answered =false;
                    while(!$answered){
                        $prompt = $this->color(" The seeder file: '", "black", "yellow"). $this->color($seederFullname, "blue", "yellow").  $this->color("' exist, do you want to override? Y or N ", "black", "yellow");
                        $input =  $this->readLine($prompt);
                        if($input != "n" && $input != "y"){
                            echo "Please press 'Y' for yes and 'N' for no\n";
                            continue;
                        } 
                        if(strtolower($input) == "y"){
                           
                            $write = $this->executeWrite($newSeederFile, $seederContent);
                            if($write){
                                $this->success(["The seeder file: '".$seederFullname."' has been created successfully in the directory: '{$dir}'. Complete your seeding template and run: ", "seed:db",""]);
                            }

                            $answered = true;
                        }else{
                            $answered = true;
                        }
                    }
                }else{
                    $write = $this->executeWrite($newSeederFile, $seederContent);
                    if($write){
                        $this->success(["The seeder file: '".$seederFullname."' has been created successfully in the directory: '{$dir}'. Complete your seeding template and run: ", "seed:db",""]);
                    }
                }

                break;
            default:
                # code...
                break;
        }
        
    }
    
    private function getTemplate($templateType, $templateName, $placeholderProp=[]){
        //$placeholderProp : ["placeHolderValue" => ["lines" => [line1, line2, ...], "placeHolder" => "placeHolderName"]] 
        //["placeHolderValue" => ["lines" => [6, 12, 16], "placeHolder" => "displayName"]]
        /**
         * @param string $templateName      : The template name
         * @param string $templateType      : The template type to be built, which is also used to get the correct path to the template file
         * @param array  $placeholderProp   : Holds the line numbers, the placeholder name and value. Here is the sample struture below:
         *   
         *                                  [
         *                                      "Sample-Title" =>[
         *                                          "lines" => [3], 
         *                                          "placeHolder" => "pageTitle"
         *                                      ], 
         *                                      "users" => [
         *                                          "lines" => [4, 10], 
         *                                          "placeHolder" => "displayName"
         *                                      ]
         *                                  ]
         *  In the above example, The place holder with name "PageTitle" will be replace with 'Sample-Title' on line 3, and 
         *  the placeholder with name 'displayName', will be replace with 'users' on line 4 and 18 
         *
         */

        $file = "";
        $contents = "";
        $n = 0;
        $dir = $this->configs->appRootDir."/app/assets/templates/";
 
        if($templateType == "class"){
            $dir .= "classes/";
        }else if($templateType == "pre-madeMiddleware"){
            $dir .= "classes/middlewares/";
        }else if($templateType == "schema"){
            $dir .= "schemas/";
        }else if($templateType == "display"){
            $dir .= "displays/";
        }else if($templateType == "style"){
            $dir .= "styles/";
        }else{
            $dir .= "others/";
        }

        $file           = $dir.$templateName.".zlt";
        $fileHandler    = fopen($file, "r");
        $replace        = count($placeholderProp) > 0;

        while(!feof($fileHandler)){
            $lineContent = fgets($fileHandler);

            $tempLine = $lineContent;

            if ($replace){
                foreach ($placeholderProp as $placeholderContent => $placeholderData) {
        
        
                    $lines = $placeholderData["lines"];
    
                    $placeHolderName = $placeholderData["placeHolder"];

                    if(in_array($n+1, $lines)){
                        $tempLine = str_replace($placeHolderName, $placeholderContent, $tempLine);
                    }else{
                       $tempLine = $tempLine;
                    }
    
                }
            }
            
            $contents .= $tempLine;
            $n++;
        }

        fclose($fileHandler);

        return $contents;
    }

    private function getOptions($shortDef="", $longDef=[]){
        /**
         * option type: flag = option, data = option:, both = option::
         * Flag: requires no data, 
         * Data: requires value, 
         * Both: May either be flag or data
         * 
         * 
         * Return Data type is an array of the structure below:
         *  [
         *      'optionName' => [
         *          'type' => "data|flag|both",
         *          'passed' => 1 | 0
         *          'data' => optionValue
         *      ],
         *      ..
         *      ..
         * ]
         * 
         */

        global $argv;
        $options = [

        ];

        //build definition
        
        //short option
        if(strlen($shortDef) > 0){
            $shortOpts = str_split($shortDef);
            $n=0;
            if(count($shortOpts) > 0){
                foreach ($shortOpts as $shortOpt) {
                    $current    = $shortOpt;
                    $required   = Process::array($shortOpts)->next($n, 1);
                    $optional   = Process::array($shortOpts)->next($n, 2);
                    $n++;
        
                    if($current != ":" && $required != ":"){// That is x
                        $options[$current] = ["type" => "flag"];
                    }else if($current != ":" && $required == ":" && $optional != ":"){ // That is x:
                        $options[$current] = ["type" => "data"];
                    }else if($current != ":" && $required == ":" && $optional == ":"){ // That is x::
                        $options[$current] = ["type" => "both"];
                    }else if($current == ":"){ // skip until next option is reached
                        continue;
                    }

                    $options[$current]["passed"] = 0;
                }
            }
        }
        
        

        //long option
        if(count($longDef) > 0){
            foreach ($longDef as $longOpt) {
                $longOptProp    = str_split($longOpt);
                $length         = count($longOptProp);
                
                $l2   = $longOptProp[($length-2)]; //second to the last character
                $l1   = $longOptProp[($length-1)]; //The last character
    
                if($l2 != ":" && $l1 != ":"){// That is xxxx
                    $options[trim($longOpt, ":")] = ["type" => "flag"];
                }else if($l1 == ":" && $l2 != ":"){ // That is xxxx:
                    $options[trim($longOpt, ":")] = ["type" => "data"];
                }else if($l1 == ":" && $l2 == ":"){ // That is xxxx::
                    $options[trim($longOpt, ":")] = ["type" => "both"];
                }
                
                $options[trim($longOpt, ":")]["passed"] = 0;
            }
        }


        //Compute options
        $n=0;

        foreach ($argv as $arg) {

            //check for short and long option
            $char1 = Process::string($arg)->charAt(1);
            $char2 = Process::string($arg)->charAt(2);

            if( $char1 == "-" &&  $char2 == "-" ){ //long option
                $option     = explode("=", trim($arg, "-"));
     
                if (array_key_exists($option[0], $options)){

                    $optionType = $options[$option[0]]["type"];

                    if ($optionType == "data" || $optionType == "both"){// set data if exist
                        if($optionType == "data"){ //requires data
                        
                            if(count($option) > 1){ //only add option if data exist
                                //check if value is passed
                                if (strlen($option[1]) > 0){
                                    $options[$option[0]]["data"] = $option[1];
                                }
                            }
                        
                        }else{ //add option if data exist or not
                            $data = count($option) > 1?$option[1]:"";
                            $options[$option[0]]["data"] = $data;
                        }
                        
                    }

                    $options[$option[0]]["passed"] = 1;
                }

            }else if($char1 == "-" &&  $char2 != "-"){ //short option

                $mergeOptions = str_split(trim($arg, "-"));

                foreach ($mergeOptions as $sOption) {
                    if (isset($options[$sOption])){

                        $optionType = $options[$sOption]["type"];

                        if ($optionType == "data" || $optionType == "both"){// set data if exist

                            if($optionType == "data"){ //requires data
                                $data = Process::array($argv)->next($n, 1);

                                if(strlen($data) > 0){//only add option if data exist
                                    $options[$sOption]["data"] = $data;
                                }

                            }else{
                                $data = Process::array($argv)->next($n, 1);
                                $options[$sOption]["data"] = $data;
                            }
                        }else{
                            $options[$sOption]["data"] = null;
                        }

                        $options[$sOption]["passed"] = 1;

                    }else{
                        continue;
                    }
                }

            }else{
                $n++;
                continue;
            }
            $n++;
        }

        //Get options
        foreach ($options as $option => $properties) {
            if ($properties["passed"] == 0){// remove undefined optons
                unset($options[$option]);
            }
        }

        
        return $options;

    }

    // CLI ends

}
?>
