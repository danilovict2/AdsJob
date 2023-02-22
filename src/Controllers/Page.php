<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use AdsJob\Page\PageReader;
use AdsJob\Template\Renderer;
use Http\Response;
use \AdsJob\Page\InvalidPageException;

class Page{

    private Response $response;
    private Renderer $renderer;
    private PageReader $pageReader;

    public function __construct(
        Response $response,
        Renderer $renderer,
        PageReader $pageReader
    ){
        $this->response = $response;
        $this->renderer = $renderer;
        $this->pageReader = $pageReader;
    }
    
    public function show($params){
        $slug = $params['slug'];

        try {
            $data['content'] = $this->pageReader->readBySlug($slug);
        } catch (InvalidPageException $e) {
            $this->response->setStatusCode(404);
            return $this->response->setContent('404 - Page not found');
        }

        $html = $this->renderer->render('index.php', $data);
        $this->response->setContent($html);
    }
}