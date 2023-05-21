<?php declare(strict_types = 1);
use AdsJob\Database\DB;
require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

DB::connect();
if(isset($argv[1]) && $argv[1] === 'fresh'){
    DB::migrateFresh();
}else{
    DB::migrate();
}


