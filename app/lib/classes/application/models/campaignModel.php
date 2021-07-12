<?php
class CampaignModel extends Model
{
    function __construct(){
        parent::__construct();
        $this->campaignQueries = $this->loader->loadQueryBank("campaign");
    }

    public function subscribe($data){
        $preparedSql = $this->campaignQueries::subscribe();
        return $this->db->run($preparedSql, $data);
    }
    public function isSubscribed($email){
        $preparedSql = $this->campaignQueries::isSubscribed($email);
        return $this->db->exist($preparedSql, $email);
    }
}