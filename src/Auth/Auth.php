<?php declare(strict_types = 1);

namespace AdsJob\Auth;
use AdsJob\Models\Model;
use AdsJob\Models\User;
use AdsJob\Sessions\Session;

class Auth{

    private Model $user;

    public function __construct(
        private Session $session
    ){
        if(isset($_COOKIE['user'])){
            $this->user = User::findOne(['id' => (int)$_COOKIE['user']]);
            return;
        }

        $primaryKeyValue = $this->session->get('user');
        if($primaryKeyValue){
            $primaryKey = User::primaryKey();
            $this->user = User::findOne([$primaryKey => $primaryKeyValue]);
        }
    }

    public function login(Model $user) : void{
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryKeyValue = $user->$primaryKey;
        $this->session->set('user', $primaryKeyValue);
        setcookie('user', "$primaryKeyValue", time() + 86400, secure:true, httponly: true);
    }

    public function logout(){
        unset($this->user);
        $this->session->remove('user');
        setcookie('user', '', time() - 86400, secure:true, httponly: true);
    }

    public function isGuest(){
        return !isset($this->user);
    }

    public function user(){
        return $this->user;
    }
}