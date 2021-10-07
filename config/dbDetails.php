<?php
/****************Rules*****************
 * - Use single quote to set blank values
 * - Do not change the variable names
 * - Support for dynamic environment inclusion will be added soon
 * - Do not change the case value, but you can add yours
 * - Host and user must be set
 * - user name and password must be correct
 */
require_once(dirname(__DIR__, 1)."/.env");
$charset	= 'utf8';
switch (ENVIRONMENT)
{
	case 'development':
		ini_set('display_errors', 1);
		$host = '127.0.0.1';
		$user = 'root';
		$db   = '';
		$pass = '';
		break;
	case 'testing':
		error_reporting(-1);
		ini_set('display_errors', 1);
		$host = '';
		$db   = '';
		$user = '';
		$pass = '';
		break;
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')){
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}else{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
		$host = '';
		$db   = '';
		$user = '';
		$pass = '';
		break;
}

return[
	"host"=>$host,
	"db"=>$db,
	"user" => $user,
	"pass"=>$pass,
	"charset"=>$charset
]
?>