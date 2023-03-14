<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\FrontendRenderer;
use AdsJob\Database\DB;
use Http\Response;
use Http\Request;

class Controller{

    protected Request $request;
    protected Response $response;
    protected FrontendRenderer $renderer;
    protected DB $database;

    public function __construct(
        Request $request, 
        Response $response,
        FrontendRenderer $renderer,
        DB $database
    ){
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->database = $database;
    }
}