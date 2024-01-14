<?php

use vilshub\helpers\Message;
use vilshub\validator\Validator;
use vilshub\helpers\Style;



/**
 * This class helps to perform various IO operations on files
 * @author : Stalryvil
 */

 class FileSystem 
 {
    private $file = null;
    public function __construct($file){
        if(file_exists($file)){
            $this->file = $file;
        }else{
            $msg1 = "Target file $file not found, for ".Style::color(__CLASS__, "black")." contructor. Contructor argument must be a valid file";
            trigger_error($msg1);
        }
    }

    private static function executeCallBack($callBack, $data){
     
        if(is_callable($callBack)){ //execute function
          call_user_func_array($callBack, $data);
        }

    }

    public function readContent($type, $size=null, $callBack=null){
        /**
         * @param $type String which specifies the type of file to be read. possible values are: "text" | "binary"
         * @param $callBack A function to be called for each line of file or byte read, default is null 
         */

        $type = strtolower($type);
       
        $mode = $type == "binary"? "rb":"r";

        //Validation
        $msg = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("readContent(x..)", "black")." method argument 1 must be a string";
        Validator::validateString($type, Message::write("error", $msg));  
        
        if($type != "text" && $type != "binary"){
            $msg = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("readContent(x..)", "black")." method argument 1 must be a string of value 'binary' or 'text'";
            trigger_error($msg);
        }
        if ($size != null){
            $msg = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("readContent(.x.)", "black")." method argument 2 must be an integer";
            Validator::validateInteger($size, $msg);
        }
        if ($callBack != null){
            $msg = "Invalid argument value, ".Style::color(__CLASS__."->", "black").Style::color("readContent(..x)", "black")." method argument 3 must be callable";
            Validator::validateFunction($callBack, $msg);
        }

        $fileHandler  =  fopen($this->file, $mode);
        $contents = "";
        $size = $size??4096;

        while(!feof($fileHandler)){
            // continue;
            if ($callBack == null){

                if($type == "text"){//text data
                    $contents .= fgets($fileHandler, $size);
                }else{
                    $contents .= fread($fileHandler, $size);
                }
                
            }else{
                if($type == "text"){
                    $this->executeCallBack($callBack, [fgets($fileHandler, $size)]);
                }else{
                    $this->executeCallBack($callBack, [fread($fileHandler, $size)]);
                }
                
            }
        }

        
        fclose($fileHandler);

        if ($callBack != null){
            return $contents;
        }
    }

    public static function upload($tmpFile, $path, $name){
        if(is_dir($path)){
            $des = $path."/".$name;
            if(!file_exists($des)){
                return (move_uploaded_file($tmpFile, $des));
            }
        }
    }
 }
?>