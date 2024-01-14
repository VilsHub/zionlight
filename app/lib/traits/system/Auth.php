<?php
/**
* This middleware handles authentication 
* @author Starlyvil
*/ 
trait Auth
{
    private $schemaData = [
        "tableName" => null,
        "columns"   => [
            "id"        => null,
            "password"  => null
        ]
    ];

    private function userExist($user){
        $sqlPrepared = "SELECT `{$this->schemaData['columns']['id']}` FROM `{$this->schemaData['tableName']}` WHERE `{$this->schemaData['columns']['id']}` = ?";
        $exec = $this->db->run($sqlPrepared, [$user]);

        return ($exec);
    }
    public function verify($id, $data){ //user authentication
        /**
         * @ param string $data: The raw data to be verifies. Example user password (unhashed) 
         * @ param string $id: The unique attribute to be checked for existence. Example username
         */

        $user = $this->userExist($id);
       
        if($user["rowCount"] > 0){ //User Exist
            $status = false;

            if($this->hashType == "passwordHashing"){
                $status = Hasher::passwordHashing()->verify($data, $user["data"][0][$this->schemaData["columns"]["password"]]);
            }

            return [
                "status" => $status,
                "id" => $user["data"][0]["id"]
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
                if($urls[0] != null){
                    redirect($urls[0]);
                }else{
                    httpTerminate("403");
                } 
            }
        }else{
            if($urls[0] != null){
                redirect($urls[0]);
            }else{
                httpTerminate("403");
            }
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