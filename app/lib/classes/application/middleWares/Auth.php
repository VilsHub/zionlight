<?php
/**
* This middleware handles authentication 
* @author Starlyvil
*/ 
class Auth extends Middleware
{
    function __construct(Loader $loader){
        parent::__construct($loader);
    }
    
    public function verify($schemaData, $values){ //user athentication
        /**
         * @ 
         */

        $userModel = $this->loader->loadModel("users");
        $status = $userModel->exist($schemaData, [$values[0]]);
        
          
        if($status["rowCount"] > 0){
            return [
                "status" => Hasher::passwordHashing()->verify($values[1], $status["data"][$schemaData["password"]]),
                "id" => $status["data"]["id"]
            ];
        }else{
            return ["status" => false];
        }
    }
    public function guard($sessionKey, $keyValue, $urls){
        // [$guestRedirectUrl, $userRedirectUrl]
        if(Session::exist($sessionKey)){
            if(strtolower(Session::get($sessionKey)) == strtolower($keyValue)){
                if($urls[1] != null) redirect($urls[1]);
            }else{
                Session::clean();
                if($urls[0] != null) redirect($urls[0]);
            }
        }else{
            if($urls[0] != null) redirect($urls[0]);
        }
    }
    public function allow($properties){
        foreach ($properties as $name => $value) {
            Session::set($name, $value);
        }
    }
    public static function authorize($permissions, $action, $role, $redirectUrl=null){
        if(isset($permissions[$action])){
            if(!in_array($role, $permissions[$action])){
                if($redirectUrl != null){
                    redirect($redirectUrl);
                }else{
                    die("Not authourized");
                }
            }
        }else{
            trigger_error("The permission {$action} is not defined");
        }
    }
}
?>