<?php declare(strict_types = 1);

namespace AdsJob\Exceptions;

class AuthException extends \Exception{

    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}