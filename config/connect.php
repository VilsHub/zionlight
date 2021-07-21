<?php
use vilshub\dbant\DBAnt;
require_once("dbDetails.php");
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false
];
// $pdo = new PDO($dsn, $user, $pass, $opt);		//unComment this line if connecttion details are supplied
// return ["pdo" => $pdo, "db" => new DBAnt($pdo)];	//unComment this line if connecttion details are supplied
return ["pdo" => null, "db" => null]; 				//Comment this line if connecttion details are supplied
?>