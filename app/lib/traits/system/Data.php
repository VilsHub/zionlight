<?php
trait Data {
    // Data Methods
    private function insertData($schemaName, $tableName, $insertQuery){
        //check if empty
        $reset = $this->resetSchema($schemaName, $tableName, true);
        if($reset["code"] == "r2"){ //reset and built successfully
            //insert data
            $this->configs->db->disableForeignKeyCheck();
            $run = $this->configs->db->run($insertQuery);
            $this->configs->db->enableForeignKeyCheck();
 
            return [
                "status" => $run["status"],
                "totalInserted" => $run["rowCount"]
            ];
        }else{
            return false;
        }
    }
    private function parseCellData($cell, $type){
        $parsedCellData = null;
        if(strlen($cell) == 0){ //null
            $parsedCellData = "NULL";
        }else{ //not null
            if($type == "integer"){
                $parsedCellData = $cell;
            }else if ($type == "string"){
                $cell = strpos($cell, "'") >= 0 ? str_replace("'", "\'", $cell):$cell;
                $parsedCellData = "'".$cell."'";
            }
        }
        return $parsedCellData;
    }
    private function exportData($tableName){
        $dataFile           = $this->configs->dataDir."/".$tableName.$this->configs->dataFileSuffix.".zld.sql";
        $dataEngineVersion  = $this->configs->db->link->query('select version()')->fetchColumn();
        $tableConfig        = [];
        $data               = "";
        
        //build table columns names
        $sql                = "DESCRIBE `{$tableName}`";
        $run                = $this->configs->db->run($sql);
        $totalColumns       = $run["rowCount"];
       
        $data               .= "-- Zion Light SQL Dump\n";
        $data               .= "-- App Name         : {$this->configs->appName}\n";
        $data               .= "-- Generation Time  : ".date("M d, Y \a\\t h:i A")."\n"; // Jun 20, 2022 at 05:28 PM";
        $data               .= "-- Data Engine      : ".getAppEnv("DB_ENGINE")." version {$dataEngineVersion}"."\n";
        $data               .= "-- Database Name    : ".getAppEnv("DB_DATABASE")."\n";
        $data               .= "-- Table Name       : {$tableName}"."\n\n";
        $data               .= "--\n";
        $data               .= "-- Dumping data for table `{$tableName}`\n";
        $data               .= "--\n\n";
      
        $data               .= "INSERT INTO `{$tableName}` (";

        foreach($run["data"] as $key => $tableRow){ 
            if($totalColumns == $key+1){
                $data .= "`{$tableRow["Field"]}`) VALUES\n";
            }else{
                $data .= "`{$tableRow["Field"]}`, ";
            }

            //set table config        

            if (strpos($tableRow["Type"], "int") > -1){
                $tableConfig[$tableRow["Field"]] = "integer";
            }else if (strpos($tableRow["Type"], "float") > -1){
                $tableConfig[$tableRow["Field"]] = "integer";
            }else if(strpos($tableRow["Type"], "varchar") > -1){
                $tableConfig[$tableRow["Field"]] = "string";
            }else{
                $tableConfig[$tableRow["Field"]] = "string";
            }
        }

        //build rows
        $sql            = "SELECT * FROM `{$tableName}`";
        $run            = $this->configs->db->run($sql);
        $totalRecords   = $run["rowCount"];
        
        if($totalRecords > 0){
            $rows=0;
            
            foreach($run["data"] as $key => $tableRow){

                $n=0;
                $rows++;

                foreach($tableRow as $key => $cell){
                    if($totalColumns  == $n+1){//last cell of rows
                        if($rows == $totalRecords){ //last cell of last record
                            $data .= $this->parseCellData($cell, $tableConfig[$key]).");\n";
                        }else{//last cell of other records
                            $data .= $this->parseCellData($cell, $tableConfig[$key])."),\n";
                        }
                    }else if($n==0){
                        $data .= "(".$this->parseCellData($cell, $tableConfig[$key]).", ";
                    }else{
                        $data .= $this->parseCellData($cell, $tableConfig[$key]).", ";
                    }   
                    $n++;
                }
            }

            $this->executeWrite($dataFile, $data);

            return [
                "status"=> true,
                "total" => $totalRecords
            ];
        }else{
            return [
                "status"=> false,
                "total" => 0
            ];
        }

    }
    private function importData($dataFile){
        //build values
        $data   = $this->readDumpContent($dataFile);
       
        $insertQuery    = $data["schema"];
        $tableName      = $data["tableName"];
        $totalInserted  = 0;

        //check if table exist
        $tableExist = $this->configs->db->tableExist($tableName);
       
        if ($tableExist){
            //get schema name
            $schemaName = @$this->getTableSchemaName($tableName)["schema_name"];
            $answered = false;

            if (strlen($schemaName)){
                while (!$answered) { 
                    $prompt = $this->color("\n You are about to empty the table: '$tableName' and fill it with new data. Do you proceed? Y or N ", "blue", "yellow");
                    $input =  $this->readLine($prompt);  
                    if($input != "n" && $input != "y"){
                        echo "Please press 'Y' for yes and 'N' for no\n";
                        continue;
                    } 
    
                    if(strtolower($input) == "y"){
                        $insertData = $this->insertData($schemaName, $tableName, $insertQuery);
                        if($insertData["status"]){//imported
                            $totalInserted  = $insertData["totalInserted"];
                            $status = "c0";
                        }else{//could not import data
                            $status = "c2";
                        } 
                    }
    
                    $answered = true;
                }
            }else{
                $status = "c3";// Table not tracked
            }
            
        }else{
            $status = "c1";//Table does not exist
        }

        return [
            "code" => $status,
            "tableName" => $tableName,
            "totalInserted" => $totalInserted
        ];
    }
}
?>