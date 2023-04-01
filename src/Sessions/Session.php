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
    
}