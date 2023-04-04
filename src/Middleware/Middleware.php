<?php declare(strict_types = 1);

namespace AdsJob\Middleware;


abstract class Middleware{
    
    abstract public function execute();
}