<?php declare(strict_types = 1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$injector = include('Dependencies.php');
$database = $injector->make('\AdsJob\Database\DB');

$database->migrate();

