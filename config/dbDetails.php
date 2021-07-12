<?php
require_once(__DIR__."../../.env");
switch (ENVIRONMENT)
{
  	case 'development':
		$host = '127.0.0.1';
		$db   = 'zlight';
		$user = 'root';
		$pass = '';
		break;
	case 'testing':
		error_reporting(-1);
		ini_set('display_errors', 1);
		break;
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>=')){
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
		$host = '';
		$db   = '';
		$user = '';
		$pass = '';
		break;
}
$charset = 'utf8';
?>