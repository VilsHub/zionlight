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
    private static function dynamicRouteQuery($route, $uri, $for="web"){
      $status=false;$data=[];$matched=null;
      $routeSegments      = self::segments($route);
      $uriSegments        = self::segments($uri);
      $totalURISegment    = count($uriSegments );
      $totalRouteSegment  = count($routeSegments );
      if($for == "web"){
        $validRange = $totalURISegment > 0; //url segments may be more than the route definition segments
      }else{
        $validRange = $totalURISegment == $totalRouteSegment;//url segments must be equal to the route definition segments
      }

      if($validRange){
        //loop through the routes, to test for each segment on the url
        foreach ($routeSegments as $key => $value) {  
          $matched = 0;
          if(strpos($value, "{") !== FALSE){ ////dynamic type (it could be data or regEx)
            $parsedData = self::parsedData($value);
            if(self::dynamicRouteType($parsedData) == "regex"){
              //regex type, check for match
              $parsedPattern = "/".$parsedData."/";

              if(isset($uriSegments[$key])){
                if(preg_match($parsedPattern, $uriSegments[$key])){
                  $data[] = $uriSegments[$key];
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
              if($value != $uriSegments[$key]){
                $matched=false;
                break;
              }else{
                $matched=true;
                continue;
              }
            }
          }
        }
      }
      return [
        "data"          => $data,
        "matched"       => (bool) $matched,
        "urlSegments"   => $uriSegments,
        "routeSegments" => $routeSegments 
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
              if($uriBlock[$key] != $value){
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