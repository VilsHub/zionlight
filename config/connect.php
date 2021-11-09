<?php
use vilshub\dbant\DBAnt;
$dbInfo = require_once("dbDetails.php");
if($dbInfo['isDatabaseApp']){
	$boot = new Boot($dbInfo);
	$dsn = "mysql:host={$dbInfo['host']};dbname={$dbInfo['db']};charset={$dbInfo['charset']}";
	$xdsn = "mysql:host={$dbInfo['host']};charset={$dbInfo['charset']}";
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false
	];
	$xPdo = new PDO($xdsn, $dbInfo["user"], $dbInfo["pass"], $opt);	
	if($databaseInit){
		$pdo 	= new PDO($dsn, $dbInfo["user"], $dbInfo["pass"], $opt);
		$db 	= new DBAnt($pdo);
	}else{
		$pdo 	= null;
		$db 	= null;
	}
	$boot->databaseInitCheck($dbInfo);		
	return ["pdo" => $pdo, "db" => $db, "xDB" => new DBAnt($xPdo)];
}else{
	return [];
}

?>