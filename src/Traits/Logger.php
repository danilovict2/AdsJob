<?php declare(strict_types = 1);

namespace AdsJob\Traits;


Trait Logger{

    private static function log(string $message){
        echo '[' . date('Y-m-d H:i:s') . ']' . $message . PHP_EOL;
    }
}