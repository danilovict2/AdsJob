<?php declare(strict_types = 1);

namespace AdsJob\Models;

abstract class Model{

    public function loadData($data){
        
        foreach($data as $key=>$value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }

    public function validate(){

    }
}