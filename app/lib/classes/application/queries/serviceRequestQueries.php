<?php
class ServiceRequestQueries
{
    public static function appDevelopment(){
        $sqlPrepared = "INSERT INTO `appDevRequests` SET
        `projectName` = ?,
        `projectDescription` = ?,
        `platform` = ?,
        `duration` = ?,
        `durationUnit` = ?,
        `budget` = ?,
        `budgetUnit` = ?,
        `email` = ?,
        `phone` = ?";
        return $sqlPrepared;
    }
    public static function subscribeUser(){
        $sqlPrepared = "INSERT INTO `realtimeSolutionSubscribtions` SET
        `first_name` = ?,
        `email` = ?,
        `domain` = ?,
        `startDate`  = ?,
        `active` = ?";
        return $sqlPrepared;
    }
    public static function addUserDomains(){
        $sqlPrepared = "INSERT INTO `userChannels` SET
            `userID` = ?,
            `channel` = ?,
            `channelID` = ?";
        return $sqlPrepared;
    }
    public static function bookMe(){
        $sqlPrepared = "INSERT INTO `bookDev` SET
            `taskType` = ?,
            `taskDescription` = ?,
            `dateNeeded`  = ?,
            `email` = ?,
            `phone` = ?";
            return $sqlPrepared;
    }
    public static function enroll(){
        $sqlPrepared = "INSERT INTO `trainingEnrollement` SET
        `fullName`  = ?,
        `email`  = ?,
        `phone` = ?,
        `platform`  = ?,
        `package` = ?";
        return $sqlPrepared;
    }
}
?>