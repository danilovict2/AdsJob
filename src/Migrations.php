<?php declare(strict_types = 1);
use AdsJob\Database\DB;
require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

DB::connect();
DB::migrate();


