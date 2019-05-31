<?php

ini_set('display_errors', 1);

require_once(__DIR__ . '/vendor/autoload.php');

define('CONSUMER_KEY', '*');
define('CONSUMER_SECRET', '*');
define('CALLBACK_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/login/login.php');

define('DSN', 'mysql:host=*;dbname=*');
define('DB_USERNAME', '*');
define('DB_PASSWORD', '*');

session_start();

require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/autoload.php');