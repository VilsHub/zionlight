<?php
require_once(__DIR__."/../helpers/CLIColors.php");
$config = require_once(__DIR__."/../../../../../config/app.php");
define("ROOT", dirname(__DIR__,5));
class console extends CLIColors{
    private $version  = "1.0.0.beta";
    private $appConfig;
    private $commands =[
        "create" => [
            "middleware",
            "controller",
            "model",
            "querybank",
        ],
        "start" => 1,
        "cleaninstall" => 1,
    ];
    private $argv;
    function __construct($argv, $argc)
    {
        global $config;
        $this->appConfig = $config;
        $this->argv = $argv;
        parent::__construct();

        if($argc <= 1){
            $this->showAllOptions();
        }else{
            //call command manager
            $this->commandManager($argv[1]);
        }
    }

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
        echo "\n\n";
        
        $list .= "\n".$this->color(" START", "light_purple", "black");
        $list .= "\n\t".$this->color(" - Start server", "yellow", "black")." \t".$this->color("start:@", "green", "black")."portNumber";
        echo $list;
        echo "\n\n";
    }

    private function commandManager($command){
        $CommandInfo = $this->getAction($command);
        $exec = $CommandInfo["command"];
        $this->validateCommand($exec);
        

        switch (strtolower($exec)){
            case 'create':
                $action = $CommandInfo["action"];
                $this->validateCommandAction($exec, $action);
                $this->create($action);
                break;
            case 'start':
                $this->startServer();
                break;
            case 'cleaninstall':
                $this->cleanInstall();
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
        }
        return $build;
    }

    private function validateCommand($command){
        if (!array_key_exists($command, $this->commands)){
            $this->error(["The command: ", $command, " is not supported"]);
        }
    }

    private function validateCommandAction($command, $action){
        if (!in_array($action, $this->commands[$command])){
            $this->error(["The ".strtoupper($command)." command action: ", $action, " is not supported"]);
        }
    }

    private function error($details){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $text = "  ".$details[0].$this->color($details[1], "yellow", "red").$this->color($details[2]."   ", "white", "red");
        $margin = $this->getMargin($chars+5)."\n";

        $this->write($margin, "white", "red");
        $this->write($text."\n", "white", "red");
        $this->write($margin, "white", "red");
        die("\n\n");
    }

    private function success($details){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $text = "  ".$details[0].$this->color($details[1], "yellow", "green").$this->color($details[2]."   ", "white", "green");
        $margin = $this->getMargin($chars+5)."\n";

        $this->write($margin, "white", "green");
        $this->write($text."\n", "white", "green");
        $this->write($margin, "white", "green");
        die("\n\n");
    }

    private function warning($details){  
        $chars = strlen(implode("", $details));
        echo "\n";
        $text = "  ".$details[0].$this->color($details[1], "blue", "yellow").$this->color($details[2]."   ", "black", "yellow");
        $margin = $this->getMargin($chars+5)."\n";

        $this->write($margin, "black", "yellow");
        $this->write($text."\n", "black", "yellow");
        $this->write($margin, "black", "yellow");
        die("\n\n");
    }

    private function getMargin($n){
        $space = "";
        for ($x=0; $x<$n; $x++){
            $space .= html_entity_decode("&nbsp;");
        }
        return $space;
    }

    private function create($action){
        //check for new object name
        if(isset($this->argv[2])){
            //name must only be alphabets
            if(!$this->validate("alpha", $this->argv[2])){
                $this->error(["Controller name must be alphabets only, the name:", $this->argv[2], " is not all alphabets"]);
            }
        }else{
            $this->error(["Please specify the ", $action, " name"]);
        }

        switch ($action) {
            case 'controller':
                $this->createController(ucwords($this->argv[2]));
                break;
            case 'model':
                $this->createModel(ucwords($this->argv[2]));
                break;
            default:
                # code...
                break;
        }
    }

    private function cleanInstall(){
        @rmdir("../vendor");
        //update composer file
        @buildTemplate("composer");
    }

    private function validate($type, $value){
        switch ($type) {
            case 'alpha':
                return preg_match("/^[a-zA-Z]+$/", $value);
                break;
            
            default:
                # code...
                break;
        }
    }

    private function buildTemplate($type){
        switch ($type) {
            case 'controller':
                $dir = $this->appConfig->controllersDir;
                $controllerContent =  $this->getTemplate("controller", $name);

                //write to new controller file
                $newControllerFile = ROOT.$dir."/".$name.".php";

                if($this->writeTemplate($newControllerFile, $controllerContent, "Controller")){
                    $this->success(["The controller: ",$name, " has been created successfully in the directory: ".ROOT.$dir]);
                }
                break;
            case 'model':
                $dir = $this->appConfig->modelsDir;

                $modelContent =  $this->getTemplate("model", $name.$this->appConfig->modelFileSuffix);

                //write to new model file
                $newModelFile = ROOT.$dir."/".$name.$this->appConfig->modelFileSuffix.".php";
                if($this->writeTemplate($newModelFile, $modelContent, "Model")){
                    $this->success(["The Model: ",$name.$this->appConfig->modelFileSuffix, " has been created successfully in the directory: ".ROOT.$dir]);
                }
                break;
            case 'composer':
                $composerContent =  $this->getTemplate("composer");

                //write to new composer file
                $newComposerFile = ROOT."composer.json";
                @$this->executeWrite($newComposerFile, $composerContent);
                break;
            default:
                # code...
                break;
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

    private function writeTemplate($fileName, $content, $type){
        // check if file exist
        if(!file_exists($fileName)){
           return $this->executeWrite($fileName, $content);
        }else{
            $prompt = $this->color(" The ".$type." file: ", "black", "yellow"). $this->color($fileName, "blue", "yellow").  $this->color(" exist, do you want to override? Y or N ", "blue", "yellow");
            $input =  $this->readLine($prompt);
            if(strtolower($input) == "y"){
                return $this->executeWrite($fileName, $content);
            }
        }
    }

    private function readLine($prompt){
        echo $prompt." : ";
        $callForInput = fopen("php://stdin", "r");
        $response = trim(fgets($callForInput));
        fclose($callForInput);
        return $response;
    }

    private function getTemplate($type, $className=null){
        $file = "";
        $contents = "";
        $n = 0;
        $file = dirname(__DIR__,5)."/app/assets/console/".$type.".zlt";

        $fileHandler = fopen($file, "r");
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if($n == 6){
                $contents .= str_replace("className", $className, $line);
            }else{
                $contents .= $line;
            }
            $n++;
        }
        fclose($fileHandler);
        return $contents;
    }

    private function startServer(){
        //get port
        $serverCommand = $this->getAction($this->argv[1]);
        if(array_key_exists("action", $serverCommand)){
            $port = explode("@", $serverCommand["action"]);
            $total = count($port);
            if($total < 2 || $total > 2 ){
                $this->error(["Specify server port using : " ,"@portNumber"," example: start:@3000"]);
            }

            //validate port number
            if(!is_int((int) $port[1])){
                $this->error(["The specified port : " ,$port[1]," is invalid, must be a postive initeger"]);
            }

            if((int)  $port[1] < 1){
                $this->error(["The specified port : " ,$port[1]," is invalid, must be greater than 0"]);
            }
            
        }else{
            $this->error(["Specify server port using : " ,"@portNumber ","example: start:@3000"]);
        }
        

        $command = "php -S localhost:".$port[1]." -t public/";
        $output = shell_exec("php -v");  //Check if php path exist      
        if ($output != ""){
            $this->write("ZLight App ".$this->appConfig->appName." started at 127.0.0.1:".$port[1]."\n", "green", "black");
            $this->write("Press ".$this->color("Ctrl + C", "yellow", "black"). " to shutdown server\n", "green", "black");
            shell_exec($command);
        }
    }
}
?>