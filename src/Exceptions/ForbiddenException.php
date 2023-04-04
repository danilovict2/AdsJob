<?php declare(strict_types = 1);

namespace AdsJob\Exceptions;

class ForbiddenException extends \Exception{

    protected $message = "You don't have permission to acess this page";
    protected $code = 403;
}