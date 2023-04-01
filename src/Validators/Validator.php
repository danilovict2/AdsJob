<?php declare(strict_types = 1);

namespace AdsJob\Validators;

use AdsJob\Database\DB;

class Validator{

    private const RULE_REQUIRED = 'required';
    private const RULE_EMAIL = 'email';
    private const RULE_MIN = 'min';
    private const RULE_MAX = 'max';
    private const RULE_MATCH = 'match';
    private const RULE_UNIQUE = 'unique';
    public array $errors = [];

    public function __construct(
        private array $rules,
        private DB $db,
    ){

    }

    public function addError(string $attribute, string $message){
        $this->errors[$attribute][] = $message;
    }

    public function validateForm(array $data) : bool{
        foreach($this->rules as $attribute => $rules){
            foreach($rules as $rule){
                $this->validateRule($rule, $attribute, $data);
            }
        }
        return empty($this->errors);
    }

    private function validateRule(string|array $rule, string|int $attribute, array $data) : void{
        $ruleName = is_array($rule) ? key($rule) : $rule;
        $attributeValue = $data[$attribute];
        switch($ruleName){
            case self::RULE_REQUIRED: 
                if(!$attributeValue)
                    $this->addErrorForRule($ruleName, $attribute);
                break;
            case self::RULE_EMAIL : 
                if(!filter_var($attributeValue, FILTER_VALIDATE_EMAIL))
                    $this->addErrorForRule($ruleName, $attribute);
                break;
            case self::RULE_MIN : 
                if(is_string($attributeValue) && strlen($attributeValue) < $rule['min'])
                    $this->addErrorForRule($ruleName, $attribute,['min' => $rule['min']]);
                break;
            case self::RULE_MAX : 
                if(is_string($attributeValue) && strlen($attributeValue) > $rule['max'])
                    $this->addErrorForRule($ruleName, $attribute,['max' => $rule['max']]);
                break;
            case self::RULE_MATCH : 
                if($attributeValue !== $data[$rule['match']])
                    $this->addErrorForRule($ruleName, $attribute, ['match' => $rule['match']]);
                break;
            case self::RULE_UNIQUE :
                $className = "\AdsJob\Models\\".$rule['unique'];
                if($this->db->exists($className::tableName(), $attribute, $attributeValue)){
                    $this->addErrorForRule($ruleName, $attribute, ['field' => $attribute]);
                }
                break;
        }
    }

    private function addErrorForRule(string $rule, string|int $attribute, array $params = []) : void{
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
            self::RULE_UNIQUE => 'Record with this {field} already exists'
        ];
    }

}