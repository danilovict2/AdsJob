<?php declare(strict_types = 1);

namespace AdsJob\Controllers;

class ProfileController extends Controller{

    public function index($params){
        $user_id = $params['user_id'];
        $html = $this->renderer->render('profile.html');
        $this->response->setContent($html);
    }
}