<?php declare(strict_types = 1);

namespace AdsJob\Sessions;

class Session{

    private const FLASH_KEY = 'flash_messages';

    public function __construct(){
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage){
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function __destruct(){
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flashMessages as $key => &$flashMessage){
            if(isset($flashMessage['remove']) && $flashMessage['remove']){
                unset($flashMessages[$key]);
            }        
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function setFlash(mixed $key, string $message) : void{
        $_SESSION[self::FLASH_KEY][$key] = [
            'removed' => false,
            'value' => $message,
        ];
    }

    public function getFlash(mixed $key){
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set(mixed $key, mixed $value) : void{
        $_SESSION[$key] = $value;
    }

    public function get(mixed $key){
        return $_SESSION[$key] ?? false;
    }

    public function remove(mixed $key){
        unset($_SESSION[$key]);
    }

    public function csrf_token(){
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token; 
        echo "<input type='text' value='$token' style='display: none' name='csrf_token'>";
    }

    public function validateToken(string $token){
        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== $token) {
            return false;
        }
        unset($_SESSION['csrf_token']);
        return true;
    }
    
}