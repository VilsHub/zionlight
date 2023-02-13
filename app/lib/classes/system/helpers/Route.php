<?php
 
use vilshub\helpers\Message;
use vilshub\helpers\Get;
use vilshub\helpers\Style;
use Dice\Dice;
use vilshub\http\Request;
use vilshub\validator\Validator;

class Route{
    private static function checkRouteType($route){
      if(strpos($route, "{") === FALSE){
        return "static";
      }else{
        return "dynamic";
      }
    }
    private static function dynamicRouteType($dynamicSegment){
      if(preg_match('/[^a-zA-Z0-9]/', $dynamicSegment)){
        return "regex";
      }else{
        return "data";
      }
    }
    private static function parsedData($pattern){
      $parsedPattern = trim($pattern, "{}");
      return $parsedPattern;
    }
    public static function segments($path){
      return explode("/",trim($path, "/"));
    }
    private static function dynamicRouteQuery($route, $uri){
      $data=[];$matched=null;
      $routeSegments      = self::segments($route);
      $uriSegments        = self::segments($uri);
      $totalURISegment    = count($uriSegments);
      $totalRouteSegment  = count($routeSegments);
      $segmentDifference  = $totalURISegment - $totalRouteSegment;
      $displayFile        = null;
      $lastTrail          = null;

      if(PHP_SAPI != "cli"){//web
        $validRange = $segmentDifference == 0 || $segmentDifference == 1; //url segments may be more than the route definition segments with only 1
      }else{
        $validRange = $totalURISegment == $totalRouteSegment;//url segments must be equal to the route definition segments
      }

      if($segmentDifference == 1){// has last trail
        $lastTrail = $uriSegments[$totalURISegment-1];
        $trailType = (strpos($lastTrail, "{") !== FALSE)?"dynamic":"static";
      }

      $n=0;

      if($validRange){

        //loop through the routes, to test for each segment on the url
        foreach ($routeSegments as $key => $value) {  
          $n++;
          $matched = 0;

          //get set display file
          if (strpos($value, ":") !== false) $displayFile = ltrim($value, ":");
          

          if(strpos($value, "{") !== FALSE){ //dynamic type (it could be data or regEx)
            $parsedData = self::parsedData($value);
            if(self::dynamicRouteType($parsedData) == "regex"){
              //regex type, check for match
              $parsedPattern = "/".$parsedData."/";
              // echo $uriSegments[$key]."</br></br> ". $parsedData;
              if(isset($uriSegments[$key])){
                if(preg_match($parsedPattern, $uriSegments[$key])){
                  $data[] = DataParser::inText($uriSegments[$key]);
                  $matched=true;
                }else{
                  $matched=false;
                  break;
                };
              }else{
                $matched=true;// for web route validation
              }
            }else{
              if(isset($uriSegments[$key])){
                $matched=true;
                $data[] = DataParser::inText($uriSegments[$key]);
              }else{
                $matched=true;// for web route validation
              }
            }
          }else{

            if(isset($uriSegments[$key])){
              if(ltrim($value, ":") != $uriSegments[$key]){
                $matched=false;
                break;
              }else{
                $matched=true;
                continue;
              }
            }

          }

          //check if last trail
          if ($segmentDifference == 0 && $n == $totalRouteSegment){
              if ($displayFile == null){ //no display file specified, use default trail as file
                $displayFile = "default";
              }
          }

          if ($segmentDifference == 1 && $n == $totalRouteSegment){
            if($trailType == "static"){
              if ($displayFile == null){ //no display file specified, use last trail as file
                $displayFile = $lastTrail;
              }else{
                $data[] = DataParser::inText($lastTrail);
              }
            }
          }

          if($totalURISegment == 2 && $n == $totalRouteSegment){
            if ($displayFile == null){
              if ((strpos($uriSegments[0], "{") === FALSE)){
                $displayFile = $uriSegments[0];
              }
            }
          }


        }
      }

      return [
        "data"              => $data,
        "matched"           => (bool) $matched,
        "urlSegments"       => $uriSegments,
        "routeSegments"     => $routeSegments,
        "displayFile"       => $displayFile,
        "segmentDifference" => $segmentDifference
      ];
    }
    private static function executeCallBack($callBack, $data){
      if(is_string($callBack)){//create class and call method
        $controllerInfo = explode("@", $callBack);
        $className      = $controllerInfo[0];

        try {   
          $dice = new Dice;
          $buildClass = $dice->create($className);
          $methodName = $controllerInfo[1];
          call_user_func_array(array($buildClass, $methodName), $data); 
        }catch (\Throwable $th) { 
          trigger_error("");
        }        
      }else if(is_callable($callBack)){ //execute function
        call_user_func_array($callBack, $data);
      }
    }
    
    public static function validateRoute($route, $handlerOrCm, $for){
      $routeType  = self::checkRouteType($route);
      $url        = $_SERVER["REQUEST_URI"];
      if($routeType == "dynamic"){ 
        $dynamicRouteInfo = self::dynamicRouteQuery($route, $url, $for);
        if($dynamicRouteInfo["matched"] === true){
          self::executeCallBack($handlerOrCm, $dynamicRouteInfo["data"]);
        }
      }else{
        if($url == $route){
          self::executeCallBack($handlerOrCm, []);
        }
      }
    }

    public static function type($route){
      return self::checkRouteType($route);
    }

    public static function block($route, $uri){
      $routeBlock         = self::segments($route);
      $uriBlock           = self::segments($uri);
      $totalURISegments   = count($uriBlock);

      if($totalURISegments > 0 ){
        $status = true;
        foreach ($routeBlock as $key => $value) {
          $blockType  = self::checkRouteType($value);
          if($blockType == "dynamic"){//dynamic block
              //check if data or regex
              $type = self::dynamicRouteType($value);
              if($type == "regex"){
                $pattern  = self::parsedData(self::parseRegex($value));
                $parsedPattern = "/".$pattern."/";
                if(!preg_match($parsedPattern, $uriBlock[$key])){
                  $status = false;
                  break;
                }else{
                  $status = true;
                }
              }
          }else{
            if(isset($uriBlock[$key])){
              if($uriBlock[$key] != trim($value, ":")){
                $status = false;
                break;
              }
            }
          }
          if($key == 1) break;
        }
        return $status;
      }
    }

    public static function dynamicInfo($route, $uri){
      return self::dynamicRouteQuery($route, $uri);
    }

    public static function parseRegex($route){
      return str_replace(["{/", "/}"], ["{", "}"], $route);
    }
}
?>