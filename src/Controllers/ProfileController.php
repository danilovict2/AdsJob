<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Page\PageReader;
use AdsJob\Template\FrontendRenderer;
use Http\Response;
use Http\Request;

class ProfileController{

    private Request $request;
    private Response $response;
    private FrontendRenderer $renderer;
    private PageReader $reader;

    public function __construct(
        Request $request, 
        Response $response,
        FrontendRenderer $renderer
    ){
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function index($params){
        $user_id = $params['user_id'];
        $html = $this->renderer->render('profile.html');
        $this->response->setContent($html);
    }
}