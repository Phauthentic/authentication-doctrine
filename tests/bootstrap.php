<?php

define('ROOT', dirname(dirname(__FILE__)));


if (!function_exists('env')) {
    function env ($varname) {
        return getenv($varname);
    }
}

if (!getenv('PDO_DB_DSN')) {
    putenv('PDO_DB_DSN=sqlite::memory:');
}
