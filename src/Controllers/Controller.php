<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\FrontendRenderer;
use AdsJob\Page\PageReader;
use AdsJob\Database\DB;
use Http\Response;
use Http\Request;

class Controller{

    protected Request $request;
    protected Response $response;
    protected FrontendRenderer $renderer;
    protected PageReader $pageReader;
    protected DB $database;

    public function __construct(
        Request $request, 
        Response $response,
        FrontendRenderer $renderer,
        PageReader $pageReader,
        DB $database
    ){
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->pageReader = $pageReader;
        $this->database = $database;
    }
}