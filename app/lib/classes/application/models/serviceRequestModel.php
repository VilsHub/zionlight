<?php
class ServiceRequestModel extends Model
{
    function __construct(){
        parent::__construct();
        $this->serviceRequestQueries = $this->loader->loadQueryBank("serviceRequest");
    }
    public function appDev($data){
        $preparedSql = $this->serviceRequestQueries::appDevelopment();
        return $this->db->run($preparedSql, $data);
    }
    public function subscribeUser($data){
        $preparedSql = $this->serviceRequestQueries::subscribeUser();
        $this->db->startTransaction();
        return $this->db->run($preparedSql, $data);
    }
    public function addUserDomains($data){
        $preparedSql = $this->serviceRequestQueries::addUserDomains();
        $batchInsert = $this->db->batchRun($preparedSql, $data);
        (!$batchInsert)? $this->db->rollBack() : $this->db->commit();
        return  $batchInsert;
    }
    public function bookMe($data){
        $preparedSql = $this->serviceRequestQueries::bookMe();
        return $this->db->run($preparedSql, $data);
    }
    public function enroll($data){
        $preparedSql = $this->serviceRequestQueries::enroll();
        return $this->db->run($preparedSql, $data);
    }
}
?>