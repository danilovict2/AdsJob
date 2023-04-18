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
    }

    public function logout(){
        unset($this->user);
        $this->session->remove('user');
    }

    public function isGuest(){
        return !isset($this->user);
    }

    public function user(){
        return $this->user;
    }
}