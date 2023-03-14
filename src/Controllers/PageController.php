<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

use \AdsJob\Page\InvalidPageException;

class PageController extends Controller{
    
    public function show(array $params){
        $slug = $params['slug'];

        try {
            $data['content'] = $this->pageReader->readBySlug($slug);
        } catch (InvalidPageException $e) {
            $this->response->setStatusCode(404);
            return $this->response->setContent('404 - Page not found');
        }

        $html = $this->renderer->render('index.html', $data);
        $this->response->setContent($html);
    }
}