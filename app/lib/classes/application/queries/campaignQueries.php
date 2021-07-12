<?php
class CampaignQueries
{

    public static function subscribe(){
        $sqlPrepared = "INSERT INTO `newletterSub` SET
        `email` = ?,
        `status` = ?";
        return $sqlPrepared;
    }
    public static function isSubscribed(){
        $sqlPrepared = "SELECT `id` FROM `newletterSub` WHERE
        `email` = ? LIMIT 1";
        return $sqlPrepared;
    }


}