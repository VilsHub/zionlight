<?php
use vilshub\helpers\Style;

class SystemApplications
{
    private $applications   = [];
    private $appProperties  = ["apiId", "nameSpace", "configInUse", "routeFiles", "displayMaps"];
    private $appsInstances  = [];
    private $appConfig      = null;

    function __construct(){
        global $config;
        $this->appConfig = $config;
    }

    function __get($name)
    {
        switch ($name) {
            case 'ids':
                return $this->applications;
                break;
            
            default:
                if (!in_array($name, $this->applications)) trigger_error("The application, ".style::color($name, "black")." is not registered");
                return $this->appsInstances[$name];
                # code...
                break;
        }
    }
    public function add($id){
        if (!isset($this->appProperties[$id])) {
            $obj = new class{
                public $apiId           = null;
                public $nameSpace       = null;
                public $configInUse     = null;
                public $routeFiles      = null;
                public $displaySettings = null;
                public $id              = null;
            };
            $obj->id                    = $id;
            $this->appsInstances[$id]   = $obj;

            array_push($this->applications, $id);
        }
    }

    public function config($id, $property, $value){
        if (!in_array($id, $this->applications)) trigger_error("The application, ".style::color($id, "black")." is not registered");
        if (!in_array($property, $this->appProperties)) trigger_error("The application settings key, ".style::color($property, "black")." is not valid");
   
        if ($property != "displaySettings" && $property != "routeFiles" ){
            if ($property == "configInUse"){
                if ($value != "system" && $value != "vendor") trigger_error("The application settings '".style::color("configInUse", "black")."' has receieved invalid value. , value must either be '".style::color("vendor", "black")."' or '".style::color("system", "black")."'");
            }

            $this->appsInstances[$id]->{$property} = $value;
        }if($property == "routeFiles"){
            if (!isset($this->appsInstances[$id]->nameSpace)) trigger_error("The application settings '".style::color("nameSpace", "black")."' has not been specified, specify using the method'".style::color(" SystemApplicationsObj->config() ", "black")."' before attempting to set route files");
            if (!isset($this->appsInstances[$id]->configInUse)) trigger_error("The application settings '".style::color("configInUse", "black")."' has not been specified, specify using the method'".style::color(" SystemApplicationsObj->config() ", "black")."' before attempting to set route files");
            
            $nameSpace  = "/".$this->appsInstances[$id]->nameSpace;
            $space      = $this->appsInstances[$id]->configInUse == "vendor"? $this->appConfig->vendorDir.$nameSpace:$this->config->applicationDataDir.$nameSpace;
            $tempArray  = [];

            array_map(function ($key, $value) use ($space, &$tempArray){
                $tempArray[$key] = $space."/".trim($value, "/");
            },array_keys($value), array_values($value));
            
            $this->appsInstances[$id]->{$property} = (object) $tempArray;
        }if($property == "displayMaps"){
            if (!isset($this->appsInstances[$id]->nameSpace)) trigger_error("The application settings '".style::color("nameSpace", "black")."' has not been specified, specify using the method'".style::color(" SystemApplicationsObj->config() ", "black")."' before attempting to set route files");
            if (!isset($this->appsInstances[$id]->configInUse)) trigger_error("The application settings '".style::color("configInUse", "black")."' has not been specified, specify using the method'".style::color(" SystemApplicationsObj->config() ", "black")."' before attempting to set route files");

            $nameSpace  = "/".$this->appsInstances[$id]->nameSpace;
            $space      = $this->appsInstances[$id]->configInUse == "vendor"? $this->appConfig->vendorDir.$nameSpace:$this->config->applicationDataDir.$nameSpace;
            $tempArray  = [];

            $tempArray["displayDir"]    = $space."/".trim($value["displayDir"], "/");
            $tempArray["loadDirName"]   = trim($value["loadDirName"], "/");
            $tempArray["xhrDirName"]    = trim($value["xhrDirName"], "/");
            $tempArray["plugsDir"]      = trim($value["plugsDir"], "/");
            $tempArray["fragmentsDir"]  = trim($value["fragmentsDir"], "/");
            
            $this->appsInstances[$id]->{$property} = (object) $tempArray;
        }
    }

    public function getLoadBase($appId, $displayBlockName){
        return $this->appsInstances[$appId]->displayMaps->displayDir."/".$displayBlockName."/".$this->appsInstances[$appId]->displayMaps->loadDirName;
    }

    public function getDisplayBlock($appId, $block, $displayBlockFile){
        return $this->appsInstances[$appId]->displayMaps->displayDir."/".$block."/".$displayBlockFile;
    }

    public function getFragment($appId, $block, $fragmentFile){
        require_once($this->appsInstances[$appId]->displayMaps->displayDir."/".$block."/".$this->appsInstances[$appId]->displayMaps->fragmentsDir."/".$fragmentFile);
    }
}
?>