<?php

include_once "classes/error.php";


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'main');
define('DB_PASSWORD', '');
define('DB_NAME', 'appcenter');
define("ALLOW_REGISTER", true);

global $link;

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    Errors::killapp("Database connection could not be successfully established. Application will now stop all processes.", "App Crashed");
}
