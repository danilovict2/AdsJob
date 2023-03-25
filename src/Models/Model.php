<?php declare(strict_types = 1);

namespace AdsJob\Models;

abstract class Model{

    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_MATCH = 'match';
    public array $errors = [];

    public function loadData($data) : void{
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }

    public function validateRequest(){
        foreach($this->rules() as $attribute => $rules){
            foreach($rules as $rule){
                $ruleName = is_array($rule) ? $rule[0] : $rule;
                if(!$this->validateRule($ruleName, $this->$attribute)){
                    $this->addError($ruleName, $attribute);
                }
            }
        }
    }

    private function validateRule(string $rule, string|int $attribute) : bool{
        if($rule === self::RULE_REQUIRED && !$attribute)
            return false;
        return true;
    }

    private function addError(string $rule, string|int $attribute) : void{
        $message = $this->errorMessages()[$rule] ?? '';
        $this->errors[$attribute][] = $message;
    }

    private function errorMessages() : array{
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field is {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
        ];
    }

    protected abstract function rules() : array;
}