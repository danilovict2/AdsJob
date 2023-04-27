<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

class SearchController extends Controller{

    public function show(){
        $html = $this->renderer->render('searchResults.html', $this->requiredData);
        $this->response->setContent($html);
    }
}