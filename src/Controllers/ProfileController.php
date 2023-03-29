<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

class ProfileController extends Controller{

    public function index(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('profile.html');
        $this->response->setContent($html);
    }

    public function reviews(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('reviews.html');
        $this->response->setContent($html);
    }

    public function edit(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('editProfile.html');
        $this->response->setContent($html);
    }
}