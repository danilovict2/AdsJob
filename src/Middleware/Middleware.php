<?php declare(strict_types = 1);

namespace AdsJob\Middleware;

use AdsJob\Controllers\Controller;

abstract class Middleware{
    
    abstract public function execute(string $action) : void;
}