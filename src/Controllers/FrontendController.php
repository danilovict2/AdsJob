<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Template\Renderer;
use Http\Response;
use Http\Request;

class FrontendController{

    private Request $request;
    private Response $response;
    private Renderer $renderer;

    public function __construct(
        Request $request, 
        Response $response,
        Renderer $renderer
    ){
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;
    }

    public function index(){
        $data = [
            'name' => $this->request->getParameter('name', 'stranger'),
        ];
        $html = $this->renderer->render('index.html.twig', $data);
        $this->response->setContent($html);
    }

    public function showPostFindJob(){
        $html = $this->renderer->render('post-find-job.html.twig');
        $this->response->setContent($html);
    }

    public function showLogin(){
        $html = $this->renderer->render('login.html.twig');
        $this->response->setContent($html);
    }

    public function showRegister(){
        $html = $this->renderer->render('register.html.twig');
        $this->response->setContent($html);
    }
}