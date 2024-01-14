<?php
trait Database{
    // Database starts
    private function databaseCheck($name, $target="app"){
        if(!$this->databaseExist($name, $target)){
            $msg = $this->getMargin(105)."\n";
            $msg .= "  Database initialization has not been executed. Please run the command: ";
            $this->write($msg, "white", "red");
            $msg = "php zlight initialize:database  \n";
            $this->write($msg, "yellow", "red");
            $msg = $msg = $this->getMargin(105)."\n";
            $this->write($msg, "white", "red");
            die;
        }
    }
    private function databaseExist($name, $target="app"){
        if(strlen($name) == 0){
            return false;
        }else{
            $sql = "SHOW DATABASES LIKE '{$name}'";
            if($target == "app"){
                $run = $this->configs->pdo->query($sql);
            }else{
                $run = $this->configs->xPDO->query($sql);
            }
            
            return $run->rowCount() > 0;
        }
    }
    private function executeCreateDb($name){
        $sql = "CREATE DATABASE `{$name}`";
        $run = $this->configs->xPDO->query($sql);
        return $run->rowCount() > 0;
    }
    private function createDatabase(){
        $name = ucwords($this->argv[2]);
    
        if(!$this->validate("name", $name)){
            $this->error(["The supplied database name '{$name}' is invalid. Please specify a valid name", "", ""]);
        }

        $exist = $this->databaseExist($name);
    
        if(!$exist){ // create it
            
            if(getAppEnv("DB_DATABASE") == $name){ //Already set
                $this->warning(["The database: ", "'{$name}'", "is already set"]);
            }else{ //new database name
                $createDb = $this->executeCreateDb($name);
                if($createDb){
                    $this->success(["The database:", $name, " has been created successfully"]);
                }else{
                    $this->error(["Error encountered while creating the database: ", "'{$name}'", ""]);
                }
            }
        }else{
            $this->error(["The database ", "'{$name}'", "already exist"]);
        }
    }
    private function getDBConfigFile($writes, $info){
        //file line starts from 0
        $file       = $this->configs->envFile;
        $contents   = "";
        $fileHandler = fopen($file, "r");
        $sets = [
            "pass" => [
                "status"=>false,
                "set"=>in_array("pass", $writes)
            ],
            "user" => [
                "status"=>false,
                "set"=>in_array("user", $writes)
            ],
            "charset" => [
                "status"=>false,
                "set"=>in_array("charset", $writes)
            ],
            "database" => [
                "status"=>false,
                "set"=>in_array("database", $writes)
            ],
        ];
        $updates = [];
        $lastValues = [
            "database" => $info["database"]["old"],
            "user"=>$info["user"]["old"],
            "pass"=>$info["pass"]["old"],
            "charset"=>$info["charset"]["old"]
        ];

        $oldPassword = strlen($info["pass"]["old"])>0?$info["pass"]["old"]:"''";
        $newPassword = strlen($info["pass"]["old"])>0?$info["pass"]["new"]:"'".$info["pass"]["new"]."'";
        $oldDatabase = strlen($info["database"]["old"])>0?$info["database"]["old"]:"''";
        $newDatabase = strlen($info["database"]["old"])>0?$info["database"]["new"]:"'".$info["database"]["new"]."'";
        $oldCharset  = strlen($info["charset"]["old"])>0?$info["charset"]["old"]:"''";
        $newCharset  = strlen($info["charset"]["old"])>0?$info["charset"]["new"]:"'".$info["charset"]["new"]."'";

        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
    
            if(strpos($line, 'DB_USER') > -1){//user
                if($sets["user"]["set"] && !$sets["user"]["status"]){
                    $contents .= str_replace($info["user"]["old"], $info["user"]["new"], $line, $count);
                    if($count > 0){
                        $lastValues["user"] = $info["user"]["new"];
                        $sets["user"]["status"] = true;
                        array_push($updates, "user");
                        addEnv("DB_USER", $info["user"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }
            }else if(strpos($line, 'DB_DATABASE') > -1){//database
                if($sets["database"]["set"] && !$sets["database"]["status"]){
                    $contents .= str_replace($oldDatabase , $newDatabase , $line, $count);
                    if($count > 0){
                        $lastValues["database"] = $info["database"]["new"];
                        $sets["database"]["status"] = true;
                        array_push($updates, "database");
                        addEnv("DB_DATABASE", $info["database"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }   
            }else if(strpos($line, 'DB_PASSWORD') > -1){//password
                if($sets["pass"]["set"] && !$sets["pass"]["status"]){
                    $contents .= str_replace($oldPassword, $newPassword, $line, $count);
                    if($count > 0){
                        $lastValues["pass"] = $info["pass"]["new"];
                        $sets["pass"]["status"] = true; 
                        array_push($updates, "pass");
                        addEnv("DB_PASSWORD", $info["pass"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                }   
            }else if(strpos($line, 'DB_CHARSET') > -1){//Database charater set
                if($sets["charset"]["set"] && !$sets["charset"]["status"]){
                    $contents .= str_replace($oldCharset, $newCharset, $line, $count);
                    if($count > 0){
                        $lastValues["charset"] = $info["charset"]["new"];
                        $sets["charset"]["status"] = true;
                        array_push($updates, "charset");
                        addEnv("DB_CHARSET", $info["charset"]["new"]);
                    } 
                }else{
                    $contents .= $line;
                } 
            }else{
                $contents .= $line;
            }  
        }

        fclose($fileHandler);

        return [
            "updates"   => count($updates),
            "contents"  => $contents,
            "values"    => $lastValues
        ];
    }
    private function writeDatabaseInfo($writes, $info){
        $newDbInfoContent =  $this->getDBConfigFile($writes, $info);
        if($newDbInfoContent["updates"] == count($writes)){ //content updated for writing
            //write

            if($this->executeWrite($this->configs->envFile, $newDbInfoContent["contents"])){
                return[
                    "status" => true,
                    "values" => $newDbInfoContent["values"]
                ];
            }else{
                return[
                    "status" => false,
                    "values" => $newDbInfoContent["values"]
                ];
            };
        }else{
            return[
                "status" => false,
                "values" => $newDbInfoContent["values"]
            ];
        }
    }
    private function validateDBPassword($host, $user, $password){
        try {
            $xdsn = "mysql:host={$host}";
            $opt = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ];
            new PDO($xdsn, $user, $password, $opt);	
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    private function pcd(){
        $this->info(["Your current database is: ",getAppEnv("DB_DATABASE"),""]);
    }
    // Database ends
}
?>