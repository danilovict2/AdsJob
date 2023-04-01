<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\FrontendRenderer;
use AdsJob\Database\DB;
use Http\Response;
use Http\Request;
use AdsJob\Sessions\Session;

class Controller{

    public function __construct(
        protected Request $request, 
        protected Response $response,
        protected FrontendRenderer $renderer,
        protected DB $database,
        protected Session $session,
    ){
        
    }
}