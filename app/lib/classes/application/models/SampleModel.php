<?php
/**
* This model handles.......
* @author Your name
*/

class SampleModel extends Model
{
    function __construct(Loader $loader){
        parent::__construct($loader);
        // $this->FileNameQueries = $this->loader->loadQueryBank("queryFileName"); //Without the Model suffix
    }

    public function sampleGetUsers($data){
        // $preparedSql = $this->FileNameQueries::query1();
        // return $this->db->run($preparedSql, $data);
    }
    public function sampleAddUsers($email){
        // $preparedSql = $this->FileNameQueries::query2($email);
        // return $this->db->exist($preparedSql, $email);
    }
}