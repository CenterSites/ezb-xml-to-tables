<?php

$host = 'mysqlmaster.huvemanode.hypernode.io';
$db   = 'EzBase';
$user = 'app';
$pass = 'msXu6oeBhxOmPeL2YAqFuIoQ9jND3s5x';
$charset = 'utf8mb4_unicode_ci';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = mysqli_connect($host, $user, $pass, $db);
    mysqli_set_charset($mysqli, $charset);
} catch (\mysqli_sql_exception $e) {
     throw new \mysqli_sql_exception($e->getMessage(), $e->getCode());
}
//unset($host, $user, $pass, $charset); // we don't need them anymore
?>