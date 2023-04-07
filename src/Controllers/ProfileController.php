<?php declare(strict_types = 1);

namespace AdsJob\Controllers;
use AdsJob\Middleware\AuthMiddleware;

class ProfileController extends Controller{

    public function middleware(){
        $this->registerMiddleware(new AuthMiddleware($this->auth,['index']));
        parent::middleware();
    }

    public function index(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('profile.html',['isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }

    public function reviews(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('reviews.html',['isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }

    public function edit(array $params) : void{
        $user_id = $params['user_id'];
        $html = $this->renderer->render('editProfile.html',['isGuest' => $this->auth->isGuest()]);
        $this->response->setContent($html);
    }
}