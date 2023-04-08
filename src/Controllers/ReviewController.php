<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Models\Job;
use AdsJob\Models\User;

class ReviewController extends Controller{

    public function create() : void{
        $html = $this->renderer->render('createReview.html',$this->requiredData);
        $this->response->setContent($html);
    }

    public function store() : void{
        
        $this->response->redirect('/');
    }
}