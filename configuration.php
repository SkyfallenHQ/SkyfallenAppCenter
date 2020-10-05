<?php

include_once "classes/errors.php";


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'main');
define('DB_PASSWORD', '');
define('DB_NAME', 'appcenter');
define("ALLOW_REGISTER", true);

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($link === false) {
    Errors::killapp("Database connection could not be successfully established. Application will now stop all processes.", "App Crashed");
}
