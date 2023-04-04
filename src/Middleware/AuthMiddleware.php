<?php declare(strict_types = 1);

namespace AdsJob\Middleware;
use AdsJob\Auth\Auth;
use AdsJob\Controllers\Controller;

class AuthMiddleware extends Middleware{

    public function __construct(
        private Auth $auth,
        private array $actions,
        private Controller $controller
    ){

    }

    public function execute(){
        if($this->auth->isGuest()){
            if(empty($this->actions) || in_array($this->controller::$action, $this->actions)){
                throw new \AdsJob\Exceptions\ForbiddenException();
            }
        }
    }
}