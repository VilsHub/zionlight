<?php
 
  use vilshub\helpers\Message;
  use vilshub\helpers\Get;
  use vilshub\helpers\Style;

class Route{
      private static function checkRouteType($route){
        if(strpos($route, "{") === FALSE){
          return "static";
        }else{
          return "dynamic";
        }
      }
      private static function dynamicRouteType($dynamicSegment){
        if(strpos($dynamicSegment, "[") !== FALSE){
          return "regex";
        }else{
          return "data";
        }
      }
      private static function parsedData($pattern){
        $parsedPattern = trim($pattern, "{}");
        return $parsedPattern;
      }
      private static function dynamicRouteQuery($route, $uri){
        $status=false;$data=[];$matched=1;$regEx=0;
        $route              = self::parseRegex($route);
        $routeSegments      = explode("/",trim($route, "/"));
        $uriSegments        = explode("/",trim($uri, "/"));
        $totalRouteSegment  = count($routeSegments);
        $totalURISegment    = count($uriSegments);

        if($totalURISegment == $totalRouteSegment){
          $matched = 0;
          foreach ($routeSegments as $key => $value) {
            if(strpos($value, "{") !== FALSE){ ////dynamic type (it could be data or regEx)
              if($status === false) $status = true;
              if(self::dynamicRouteType($value) === "data"){
                $data[] = $uriSegments[$key];
              }else{//regex type, check for match
                $regEx++;
                $parsedPattern = "/".self::parsedData($value)."/";
                if(preg_match($parsedPattern, $uriSegments[$key])){
                  $data[] = $uriSegments[$key];
                  $matched++;
                }else{
                  break;
                };
              }
            }else{
              continue;
            }
          }
        }else{
          $data[] = $data;
          $data[] = $uri;
        }

        return [
          "data"        => $data,
          "status"      => (bool) $status,
          "matched"     => (bool) $matched == $regEx,
          "urlSegments" => $uriSegments
        ];
      }

      private static function executeCallBack($callBack, $data){
        if(is_string($callBack)){//create class and call method
          $controllerInfo = explode("@", $callBack);
          $className      = $controllerInfo[0];
          $class          = "className";

          try {         
            $classInstance  = new $$class;
            $methodName = $controllerInfo[1];
            call_user_func_array(array($classInstance, $methodName), $data); 
          }catch (\Throwable $th) { 
            trigger_error(Message::write("error", $th->getMessage()));
          }        
        }else if(is_callable($callBack)){ //execute function
          call_user_func_array($callBack, $data);
        }
      }
      
      public static function validateRoute($route, $handlerOrCm){
        $routeType  = self::checkRouteType($route);
        $url        = $_SERVER["REQUEST_URI"];

        if($routeType == "dynamic"){ 
          $dynamicRouteInfo = self::dynamicRouteQuery($route, $url);
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
        return preg_match("/".str_replace("/", "\/", $route)."[a-zA-Z0-9\?\.\-\_\/]{0,}$/", $uri);
      }
      
      public static function for($type, $apiId){
        $apiID = strtolower(explode("/",$_SERVER["REQUEST_URI"])[1]);
        if(strtolower($type) == "api"){
            return $apiID == $apiId ? true : false;
        }else{
            return $apiID != $apiId ? true : false;
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