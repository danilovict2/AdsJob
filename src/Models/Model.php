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

    public function validateRequest() : bool{
        foreach($this->rules() as $attribute => $rules){
            foreach($rules as $rule){
                $this->validateRule($rule, $this->$attribute);
            }
        }
        return empty($this->errors);
    }

    private function validateRule(string|array $rule, string|int $attribute) : void{
        $ruleName = is_array($rule) ? $rule[0] : $rule;
        switch($ruleName){
            case self::RULE_REQUIRED: 
                if(!$attribute)
                    $this->addError($ruleName, $attribute);
                break;
            case self::RULE_EMAIL : 
                if(!filter_var($attribute, FILTER_VALIDATE_EMAIL))
                    $this->addError($ruleName, $attribute);
                break;
            case self::RULE_MIN : 
                if(is_string($attribute) && strlen($attribute) < $rule['min'])
                    $this->addError($ruleName, $attribute,['min' => $rule['min']]);
                break;
            case self::RULE_MAX : 
                if(is_string($attribute) && strlen($attribute) > $rule['max'])
                    $this->addError($ruleName, $attribute,['max' => $rule['max']]);
                break;
            case self::RULE_MATCH : 
                if($attribute !== $this->{$rule['match']})
                    $this->addError($ruleName, $attribute, ['match' => $rule['match']]);
                break;
        }
    }

    private function addError(string $rule, string|int $attribute, array $params = []) : void{
        $message = $this->errorMessages()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", (string)$value, $message);
        }
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