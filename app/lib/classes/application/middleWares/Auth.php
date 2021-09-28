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
    public function user($schemaData, $values){ //user athentication
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
    public function logUser($sessionKey, $value){
        Session::set($sessionKey, $value);
    }
}
?>