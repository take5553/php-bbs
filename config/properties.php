<?php
define('DATABASE_NAME', 'bbs');
define('DATABASE_USER', 'bbs');
define('DATABASE_PASSWORD', $_SERVER['PHP_BBS']);
define('DATABASE_HOST', 'localhost');

define('PDO_DSN', 'mysql:dbname=' . DATABASE_NAME . ';host=' . DATABASE_HOST . ';charset=utf8mb4');
