<?php
  use vilshub\helpers\Message;
  use vilshub\helpers\Get;
  use vilshub\helpers\Style;

  
  class Fake
  {
    private $faker;
    private $tempData = [];
    private $maxRecord;

    function __construct($maxRecord, $locale = "en_US"){
        $this->faker = Faker\Factory::create($locale);
        $this->faker->addProvider(new FakerProvider($this->faker));
        $this->maxRecord = $maxRecord;
    }

    /**
     * @param string $modifier: unique, valid
     * 
     */

    public function fullName($count, $options){
      $this->generateZeroIndexes($count, "name");
      $this->tempData["name"]["data"]= [];
      $this->generateData($options, "name");
      return $this->tempData["name"]["data"];
    }

    public function firstName($count, $options){
      $this->generateZeroIndexes($count, "firstName");
      $this->tempData["firstName"]["data"] = [];
      $this->generateData($options, "firstName");
      return $this->tempData["firstName"]["data"];
    }

    public function lastName($count, $options){
      $this->generateZeroIndexes($count, "lastName");
      $this->tempData["lastName"]["data"] = [];
      $this->generateData($options, "lastName");
      return $this->tempData["lastName"]["data"];
    }

    public function dateTime($count, $options){
      /**
       * @param string $options: format:validDateFormatValue|unix
       * Example: "format:Y-m-d" , "format:unix"
       */

      $this->generateZeroIndexes($count, "dateTime");
      $this->tempData["dateTime"]["data"] = [];
      $this->generateData($options, "dateTime");
      return $this->tempData["dateTime"]["data"];
    }

    public function text($count, $options){
      /**
       * @param string $options: chars:numberOfCharacters
       * Example: "chars:200" 
       */
      
      $this->generateZeroIndexes($count, "text");
      $this->tempData["text"]["data"] = [];
      $this->generateData($options, "text");
      return $this->tempData["text"]["data"];
    }

    public function userName($count, $options){
   
      $this->generateZeroIndexes($count, "userName");
      $this->tempData["userName"]["data"] = [];
      $this->generateData($options, "userName");
      return $this->tempData["userName"]["data"];

    }

    public function age($count, $options){
      /**
       * @param string $options: range:minimumDate,maximumDate 
       * Example: "range:18,48 " 
       */
      
      $this->generateZeroIndexes($count, "age");
      $this->tempData["age"]["data"] = [];
      $this->generateData($options, "age");
      return $this->tempData["age"]["data"];
    }

    public function email($count, $options){
      $this->generateZeroIndexes($count, "email");
      $this->tempData["email"]["data"] = [];
      $this->generateData($options, "email");
      return $this->tempData["email"]["data"];
    }

    private function generateData($options, $dataKey){

      $customProviders  = ["age", "firstName", "lastName"];
      $specialDataTypes = ["dateTime", "text", "userName"];
      $total = 0;
      $parsedOptions    = $this->parseOptions($options);
      $index = 0;

      while ($total != $this->maxRecord){

        if (in_array($dataKey, $customProviders)) {

          if(array_key_exists("index", $parsedOptions)){

            $range      = explode(",", $parsedOptions["index"]);
            $endIndex   = null;
            $startIndex = $range[0];
            
            if(isset($range[1])) $endIndex = (int) $range[1];
            
            $data   = $this->faker->{$dataKey}($startIndex, $endIndex);

          }else if (array_key_exists("range", $parsedOptions)){
            $range      = explode(",", $parsedOptions["range"]);
            $endIndex   = null;
            $startIndex = $range[0];
            
            if(isset($range[1])) $endIndex = (int) $range[1];
            
            $data   = $this->faker->{$dataKey}($startIndex, $endIndex);
          }

          // Custom provider firstName
          if ($dataKey == "firstName"){
            $name = $this->faker->name();
            $nameParts = explode(" ", $name);
            $data = (preg_match("/^[a-zA-Z]+\./", $nameParts[0]))? $nameParts[1]: $nameParts[0];
          }

          // Custom provider firstName
          if ($dataKey == "lastName"){
            $name = $this->faker->name();
            $nameParts = explode(" ", $name);
            $totalParts = count($nameParts);

            $data = (preg_match("/^[a-zA-Z]+\./", $nameParts[$totalParts-1]))? $nameParts[$totalParts-2]: $nameParts[$totalParts-1];
          }

        }else if(in_array($dataKey, $specialDataTypes)){

          if ($dataKey == "dateTime"){
            $format   =  isset($parsedOptions["format"])?$parsedOptions["format"]:"Y-m-d";

            if ($format == "unix"){
              $data     = $this->faker->unixTime();
            }else{
              $data     = $this->faker->{$dataKey}()->format($format);
            }
            
          }else if($dataKey == "text"){
            $chars  = $parsedOptions["chars"];
            $data   = $this->faker->{$dataKey}($chars);
          }else if($dataKey == "userName"){
            $data   = $this->faker->{$dataKey}();
            
            if(array_key_exists("length", $parsedOptions)){
              $length  = $parsedOptions["length"];
              $data = str_split($data, $length)[0];
            }
          }
          
        }else{
          $data = $this->faker->{$dataKey}();  
        }
        
        //Global options
        if (array_key_exists("unique", $parsedOptions) && !in_array($data, $this->tempData[$dataKey]["data"])){//unique option
            $this->addData($index, $dataKey, $data);
        }else if(array_key_exists("length", $parsedOptions)){
          
          $length  = $parsedOptions["length"];
          $data = str_split($data, $length)[0];

          $this->addData($index, $dataKey, $data);

        } else {
          $this->addData($index, $dataKey, $data);
        }

        $index++;
        $total = count($this->tempData[$dataKey]["data"]);

      }

    }

    private function parseOptions($options){
      /**
     * modifier: unique, optional, required, and valid
     */

      if ($options == null) return [];

      $parsed = [];
      $configs = explode("|", $options);

      foreach ($configs as $config) {
        $parsedConfig = trim($config);

        if(preg_match("/^[a-zA-Z]+:(.)*/", $parsedConfig)){
          $properties = explode(":", $config);
          $parsed[$properties[0]] = $properties[1];
        }else{
          $parsed[$parsedConfig] = 1;
        }
      }

      return $parsed;
    }

    private function generateZeroIndexes($count, $dataKey){
      $totalIndexes = $this->maxRecord - $count;
      $this->tempData[$dataKey]["zeroIndexes"] = [];
      $n = 0;
      if ($totalIndexes > 0){
        while ($n != $totalIndexes) { 
          $index = rand(0, ($this->maxRecord-1));
          if(!in_array($index, $this->tempData[$dataKey]["zeroIndexes"])){
            $this->tempData[$dataKey]["zeroIndexes"][] = $index;
            $n++;
          }else{
            continue;
          }
        }
      }
      
    }

    private function addData($index, $dataKey, $data){
      if (count($this->tempData[$dataKey]["zeroIndexes"]) == 0){//All required
        $this->tempData[$dataKey]["data"][] = $data;
      }else{
        if (in_array($index, $this->tempData[$dataKey]["zeroIndexes"])){ //Check for zero index
          $this->tempData[$dataKey]["data"][] = null;
        }else{
          $this->tempData[$dataKey]["data"][] = $data;
        }
      }
    }
  }