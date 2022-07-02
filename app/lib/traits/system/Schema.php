<?php
trait Schema {
    // Schema Starts
    private function getSchema($name){
        $schemaFile = $this->configs->schemaDir."/".$name.".sql";
        if(file_exists($schemaFile)){
            $data = $this->readSchemaContent($schemaFile);
            $data["status"] = true;
        }else{
            $data = [
                "schema"    => null,
                "tableName" => null,
                "status"    => false
            ];
        }
        
        return [
            "data"      => $data["schema"],
            "status"    => $data["status"],
            "tableName" => $data["tableName"]
        ];
    }
    private function readDumpContent($schemaFile){
        $fileHandler = fopen($schemaFile, "r");
        $content = "";
        $tableName = "";
        $setTableName = false;
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            $comment = strpos($line, "--") > -1 ?$line[0].$line[1]:null;

            if($comment == "--"){
                if(!$setTableName){
                    if(strpos($line, "Table Name") > -1){//located file name
                        $tableName = trim(explode(":", $line)[1]);
                        $setTableName = true;
                    }
                }else{
                    continue;
                }
            }            
            $content .= $line;   
        }
        
        fclose($fileHandler);

        return [
            "tableName" => $tableName,
            "schema"    => $content
        ];
    }
    private function readSchemaContent($file){
        $fileHandler = fopen($file, "r");
        $content = "";
        $tableName = "";
        while(!feof($fileHandler)){
            $line = fgets($fileHandler);
            if(strpos($line, "name:") !== false){//located file name
                $tableName = trim(explode(":", $line)[1]);
            }
            $content .= $line;   
        }
        
        fclose($fileHandler);
        return [
            "tableName" => $tableName,
            "schema"    => $content
        ];
    }
    private function deleteSchemaFile($name){
        $schemaFile = $this->configs->schemaDir."/".$name.".sql";
        if(file_exists($schemaFile)){
            return unlink($schemaFile);
        }else{
           return false;
        }
    }
    private function createTrackTable(){
        $sql = "CREATE TABLE `zlight_schema_track` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `schema_name` VARCHAR(50) NOT NULL UNIQUE,
            `table_name` VARCHAR(50) NOT NULL,
            `build` ENUM ('0', '1') DEFAULT '0'
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        return $run = $this->configs->db->run($sql);
    }
    private function buildStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '1'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function resetStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}' AND `build` = '0'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackStatus($name){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $run = $this->configs->db->run($sql);
        return $run["rowCount"]>0;
    }
    private function trackTableName($name){
        $sql = "SELECT `table_name` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $run = $this->configs->db->getRecord($sql);
        return $run["table_name"];
    }
    private function trackedFiles(){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track`";
        $run = $this->configs->db->run($sql);
        $names = [];

        foreach ($run["data"] as $key => $value) {
            array_push($names, $value["schema_name"].".sql");
        }
        
        return $names;
    }
    private function trackCreatedSchema($name, $tableName){
        $trackStatus = $this->trackStatus($name);
        $tracked = false;

        if(!$trackStatus){// not tracked, so track
            $track = $this->trackSchema($name, $tableName);
            if($track["status"]) $tracked = true;
        }else{// tracked, just reset
            $reset = $this->resetSchema($name, $tableName, false);
            if($reset["code"] == "r1") $tracked = true;
        }
        return $tracked;
    }
    private function logSchema($name, $tableName){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $exist = $this->configs->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '1' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '1', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->configs->db->run($sql);
    }
    private function unlogSchema($name, $tableName){
        $sql = "SELECT `id` FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        $exist = $this->configs->db->exist($sql, null);
        
        if($exist){//update
            $sql = "UPDATE `zlight_schema_track` SET `build` = '0', `table_name` = '{$tableName}' WHERE `schema_name` = '{$name}'";
        }else{//insert
            $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        }
        $this->configs->db->run($sql);
    }
    private function buildSchema($name){
        $schemaData     = $this->getSchema($name);
        $isBuilt        = $this->buildStatus($name);
        if($schemaData["status"]){
            //check build
            if(!$isBuilt){
                //build
                if(strlen($schemaData["data"]) == 0){//no data to be built
                    return [
                        "status"=>false,
                        "code"=>4 //no schema file content
                    ];
                }else{//has data to be built
                    try {
                        $this->configs->db->disableForeignKeyCheck();
                        $run = $this->configs->db->run($schemaData["data"]);
                        $this->configs->db->enableForeignKeyCheck();
                        
                        if($run["status"]){
                            //log schema
                            $this->logSchema($name, $schemaData["tableName"]);
                            return [
                                "status"=>true,
                                "code"=>1
                            ];
                        }
                    }catch (\Throwable $th) {        
                        $errorMessage = "";
                        $errorMessage .= "Schema        : ". $name." \n";
                        $errorMessage .= "Error number  : ".$th->errorInfo[1]."\n";
                        $errorMessage .= "Error Message : ".$th->errorInfo[2]."\n";
                        $errorMessage .= "\n";
                        
                        $this->write("", "white", "red");
                        $this->write($errorMessage , "white", "red");
                        $this->write("", "white", "red");
                        die();
                    } 
                } 
            }else{
                return [
                    "status"=>false,
                    "code"=>3 //already built
                ]; 
            }       
        }else{
            return [
                "status"=>false,
                "code"=>2 //no schema file
            ];
        }
    }
    private function writeSchema($newSchemaFile, $schemaTemplateName, $schemaName, $tableName, $points){
        $schemaContent =  $this->getTemplate("schema", $schemaTemplateName, $tableName, $points);
        if($this->executeWrite($newSchemaFile, $schemaContent)){
            //track schema file
            return $this->trackCreatedSchema($schemaName, $tableName);
        }
    }
    private function deleteSchema($name, $tableName){
        //reset schema without building
        $resetSchema = $this->resetSchema($name, $tableName, false);
        if($resetSchema["status"]){//reset succesfully, proceed to untrack from tracker and delete
            if($this->untrackSchema($name)){
                return $this->deleteSchemaFile($name);
            }
        }else{// was not tracked
            return $this->deleteSchemaFile($name);
        }
    }
    private function dropTable($tableName){
        $this->configs->db->disableForeignKeyCheck();
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        $run = $this->configs->db->run($sql);
        $this->configs->db->enableForeignKeyCheck();
        return $run;
    }
    private function getTableSchemaName($tableName){
        $sql = "SELECT `schema_name` FROM `zlight_schema_track` WHERE `table_name` = '{$tableName}'";
        return $this->configs->db->getRecord($sql);
    }
    private function resetSchema($name, $tableName=null, $rebuild=true){
        //Check track State
        $trackTableName = $this->trackTableName($name);
        
        $replace = ($tableName != null)? true: false;
       
        $targetTable = "";
       
        if(strlen($trackTableName)){
            //reset

            try {
                // drop old
                if($replace) {
                    $this->dropTable($trackTableName); 
                    $run = $this->dropTable($tableName);
                    $targetTable = $tableName;
                }else{
                    $run =$this->dropTable($trackTableName); 
                    $targetTable = $trackTableName;
                }
                
                if($run["status"]){
                    //reset in tracker
                    $this->unlogSchema($name, $targetTable);
                    
                    if($rebuild){
                        $rebuildSchema = $this->buildSchema($name);
                        if($rebuildSchema["status"]){
                            if($rebuildSchema["code"] == 1){
                                return [
                                    "status"=>true,
                                    "code"=>"r2" // reset and built successfully 
                                ];
                            }
                        }
                    }else{
                        return [
                            "status"=>true,
                            "code"=>"r1"// reset successfully
                        ];
                    }
                }
            }catch (\Throwable $th) {  
                $errorMessage = "";
                $errorMessage .= "Schema        : ". $name." \n";
                $errorMessage .= "Error number  : ".$th->errorInfo[1]."\n";
                $errorMessage .= "Error Message : ".$th->errorInfo[2]."\n";
                $errorMessage .= "\n";
                
                $this->write("", "white", "red");
                $this->write($errorMessage , "white", "red");
                $this->write("", "white", "red");
                die();
            }  
            
        }else{
            return [
                "status" => false,
                "code" => "r4"
            ];
        }
    }
    private function getSchemaFiles(){
        $schemas = array_slice(scandir($this->configs->schemaDir), 2);
        $trackedFiles = $this->trackedFiles();
        return [
            "schemaFiles" => $schemas,
            "trackedFiles" => $trackedFiles
        ];
    }
    private function autoBuildSchema($mode="new"){
        $schemaData = $this->getSchemaFiles();
        $totalTracked = count($schemaData["trackedFiles"]);
        $totalSchema = count($schemaData["schemaFiles"]);
        $missingSchema = array_diff($schemaData["schemaFiles"], $schemaData["trackedFiles"]);
        $totalMissing = count($missingSchema);
        if($mode == "new"){//for newly created schema that are not tracked 
            foreach ($missingSchema as $key => $value) {
                $name = str_replace(".sql", "", $value);
                $state =  $this->buildSchema($name);
                if($state["status"]){
                    if($state["code"] == 1){
                        $this->success(["The schema: '{$name}' has been built successfully", "",""], false);
                    }
                }else{
                    if($state["code"] == 4){
                        $this->warning(["The schema file: '{$name}' is empty, found nothing to build", "",""]);
                    }
                }
            }
            
            if($totalMissing == 0){
                $this->warning(["The new schema found", "",""]);
            }
        }else if($mode == "tracked"){
            if($totalTracked>0){
                foreach ($schemaData["trackedFiles"] as $key => $value) {
                    $name = str_replace(".sql", "", $value);
                    $state =  $this->buildSchema($name);
                    if($state["status"]){
                        if($state["code"] == 1){
                            $this->success(["The schema: '{$name}' has been built successfully", "",""], false);
                        }
                    }else{
                        if($state["code"] == 2){
                            $this->error(["The schema: '{$name}' is not found, create one using the", " create:schema", " command"]);
                        }else if($state["code"] == 4){
                            $this->warning(["The schema file: '{$name}' is empty, found nothing to build", "",""]);
                        }else if($state["code"] == 3){
                            $this->warning(["The schema: '{$name}' has been built already. Use the command: ", " reset:schema"," to reset the schema if you want to build again"]);
                        }
                    }
                }
            }else{
                $this->warning(["No tracked schema found","",""]); 
            }
           
            if($totalMissing > 0){
                $this->warning(["The schema ".pluralizer("file","s:-1", $totalMissing).": '".implode(", ",$missingSchema)."' ".pluralizer("is","are", $totalMissing)." not tracked. If you added ".pluralizer("it","them", $totalMissing)." manually, add ".pluralizer("it","each", $totalMissing)." to tracker by using the:", " track:schema | build:schema -new ", "command"]);
            }
        }
    }
    private function autoResetSchema($rebuild=true){
        $answered = false;
        //Check if has built

        while(!$answered){
            $dLabel = $rebuild?" and rebuild again":"";
            $prompt = $this->color(" Are you sure you want to drop the entire schema {$dLabel}? Y or N ", "blue", "yellow");
            $input  =  $this->readLine($prompt);
            
            if($input != "n" && $input != "y"){
                echo "Please press 'Y' for yes and 'N' for no\n";
                continue;
            } 

            if(strtolower($input) == "y"){
                $schemaData         = $this->getSchemaFiles();
                $totalTracked       = count($schemaData["trackedFiles"]);
                $totalSchema        = count($schemaData["schemaFiles"]);
                $missingSchema      = array_diff($schemaData["trackedFiles"], $schemaData["schemaFiles"]);
                $untrackedSchema    = array_diff($schemaData["schemaFiles"], $schemaData["trackedFiles"]);

                if($totalTracked > 0){
                    foreach ($schemaData["trackedFiles"] as $key => $value) {
                        $name = str_replace(".sql", "", $value);

                        if(!$this->resetStatus($name)){//has not been reset

                            $state =  $this->resetSchema($name, null, $rebuild);

                            if($state["status"]){
                                if($state["code"] == "r2"){
                                    $this->success(["The schema: '{$name}' has been reset and built successfully", "",""], false);
                                }else if($state["code"] == "r1"){
                                    $this->success(["The schema: '{$name}' has been reset successfully without rebuilding","",""], false);
                                }
                            }
                        }else{//reset already
                            $this->warning(["The schema : '{$name}' has already been reset","",""]);
                        }
                    }
                }else{
                    $this->warning(["No tracked schema found","",""]); 
                }
               

                $totalMissing   = count($missingSchema);
                $totalUntracked = count($untrackedSchema);

                if($totalMissing > 0){
                    $this->warning(["The schema ".pluralizer("file","s:-1", $totalMissing).": '".implode(", ",$missingSchema)."' ".pluralizer("is","are", $totalMissing)." missing, if you deleted ".pluralizer("it","them", $totalMissing)." manually, clear ".pluralizer("it","each", $totalMissing)." from tracker by using the:", " untrack:schema ", "command"]);
                }

                if($totalUntracked > 0){
                    $this->warning(["The schema ".pluralizer("file","s:-1", $totalUntracked).": '".implode(", ",$untrackedSchema)."' ".pluralizer("is","are", $totalUntracked)." untracked, if you added ".pluralizer("it","them", $totalUntracked)." manually, add ".pluralizer("it","each", $totalUntracked)." to tracker by using the:", " track:schema | build:schema -new ", "command"]);
                }

                $answered = true;
            }else{
                $answered = true; 
            }
        }
          
    }
    private function untrackSchema($name){
        $sql = "DELETE FROM `zlight_schema_track` WHERE `schema_name` = '{$name}'";
        return $this->configs->db->run($sql);
    }
    private function trackSchema($name, $tableName){
        $sql = "INSERT INTO `zlight_schema_track` SET `build` = '0', `schema_name` = '{$name}', `table_name` = '{$tableName}'";
        return $this->configs->db->run($sql);
    }
    private function setupCheck(){
        if ($this->app->pingDatabaseServer()["status"]){
            $this->databaseCheck($this->dbInfo["db"]);
            $trackState = $this->configs->db->tableExist("zlight_schema_track");
            if($trackState["rowCount"] == 0){// create track table
                $this->createTrackTable();
            }
        }
    }
}
?>