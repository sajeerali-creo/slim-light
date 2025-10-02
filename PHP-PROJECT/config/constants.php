<?php
error_reporting(E_ALL & ~E_WARNING);
error_reporting(0);
ini_set('display_errors', 0);
define("ROOT_URL", "https://talentgate.in/live/creators/");
define("ADMIN_URL", "https://talentgate.in/live/creators/admin/");
define("ADMIN_UPLOAD_URL", "https://talentgate.in/live/creators/admin/uploads/");
define('BASE_APP',str_replace('\\','/',__DIR__).'/' );
define('DB_HOST', 'localhost');
define('DB_USER', 'u128863200_creators_user');
define('DB_PASS', 'Appbirds@12341');
define('DB_NAME', 'u128863200_creators');

define('API_BASE_URL', 'https://idietapi.nccauh.ae/api/');
define('GRANT_TYPE', 'password');
define('API_USERNAME', 'blite.Creotopi');
define('API_PASSWORD', 'Bl!t3#Cre0top!');
define('SCOPE', 'mobile');
define('TOKEN_ENDPOINT', 'AuthToken/GetToken');
ob_start();
session_start();
