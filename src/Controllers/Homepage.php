<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\Renderer;
use Http\Response;
use Http\Request;

class Homepage{

    private $request;
    private $response;
    private $renderer;

    public function __construct(
        Request $request, 
        Response $response,
        Renderer $renderer
    ){
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function show(){
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render('index', $data);
        $this->response->setContent($html);
    }
}