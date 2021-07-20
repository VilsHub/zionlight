<?php
class SampleModel extends Model
{
    function __construct(){
        parent::__construct(new Loader);
        // $this->FileNameQueries = $this->loader->loadQueryBank("queryFileName"); //Without the suffix
    }

    public function getUsers($data){
        // $preparedSql = $this->FileNameQueries::query1();
        // return $this->db->run($preparedSql, $data);
    }
    public function addUsers($email){
        // $preparedSql = $this->FileNameQueries::query2($email);
        // return $this->db->exist($preparedSql, $email);
    }
}