<?php
error_reporting(E_ALL & ~E_WARNING);
error_reporting(0);
ini_set('display_errors', 0);
define("ROOT_URL", "https://virammarines.com/creators/");
define("ADMIN_URL", "https://virammarines.com/creators/admin/");
define("ADMIN_UPLOAD_URL", "https://virammarines.com/creators/admin/uploads/");
define('BASE_APP',str_replace('\\','/',__DIR__).'/' );
define('DB_HOST', 'localhost');
define('DB_USER', 'u128863200_creator_user');
define('DB_PASS', 'Appbirds@12341');
define('DB_NAME', 'u128863200_creator');

define('API_BASE_URL', 'https://idietapi.nccauh.ae/api/');
define('GRANT_TYPE', 'password');
define('API_USERNAME', 'blite.Creotopi');
define('API_PASSWORD', 'Bl!t3#Cre0top!');
define('SCOPE', 'mobile');
define('TOKEN_ENDPOINT', 'AuthToken/GetToken');
ob_start();
session_start();
