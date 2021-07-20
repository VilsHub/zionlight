<?php
class SampleQueries
{

    public static function query1(){
        $sqlPrepared = "INSERT INTO `newletterSub` SET
        `email` = ?,
        `status` = ?";
        return $sqlPrepared;
    }
    public static function query2(){
        $sqlPrepared = "SELECT `id` FROM `newletterSub` WHERE
        `email` = ? LIMIT 1";
        return $sqlPrepared;
    }


}