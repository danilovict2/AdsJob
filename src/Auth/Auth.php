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
            $this->session->set('user', $_COOKIE['user']);
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
        setcookie('user', "$primaryKeyValue", time() + 86400, secure:true, path:'/');
    }

    public function logout() : void{
        setcookie('user', "", time() - 3600, '/');
        unset($this->user);
        $this->session->remove('user');
    }

    public function isGuest() : bool{
        return !isset($this->user);
    }

    public function user() : ?Model{
        return $this->user;
    }
}