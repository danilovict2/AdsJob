<?php declare(strict_types = 1);

namespace AdsJob\Validators;

class Validator{

    private const RULE_REQUIRED = 'required';
    private const RULE_EMAIL = 'email';
    private const RULE_MIN = 'min';
    private const RULE_MAX = 'max';
    private const RULE_MATCH = 'match';
    private const RULE_UNIQUE = 'unique';
    private const RULE_USER_PASSWORD = 'user_password';
    private array $errors = [];

    public function __construct(
        private array $rules,
    ){

    }

    public function getErrors() : array{
        return $this->errors;
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
        $attributeValue = $data[$attribute] ?? '';
        switch($ruleName){
            case self::RULE_REQUIRED: 
                if(!$attributeValue){
                    $this->addErrorForRule($ruleName, $attribute);
                }
                break;
            case self::RULE_EMAIL : 
                if(!filter_var($attributeValue, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($ruleName, $attribute);
                }
                break;
            case self::RULE_MIN : 
                if(isset($rule['min']) && is_string($attributeValue) && strlen($attributeValue) < $rule['min']){
                    $this->addErrorForRule($ruleName, $attribute,['min' => $rule['min']]);
                }
                break;
            case self::RULE_MAX : 
                if(isset($rule['max']) && is_string($attributeValue) && strlen($attributeValue) > $rule['max']){
                    $this->addErrorForRule($ruleName, $attribute,['max' => $rule['max']]);
                }
                break;
            case self::RULE_MATCH : 
                if(isset($data[$rule['match']]) && $attributeValue !== $data[$rule['match']]){
                    $this->addErrorForRule($ruleName, $attribute, ['match' => $rule['match']]);
                }
                break;
            case self::RULE_UNIQUE :
                $model = "\AdsJob\Models\\".$rule['unique'];
                if($model::exists($attribute, $attributeValue)){
                    $this->addErrorForRule($ruleName, $attribute, ['field' => $attribute]);
                }
                break;
            case self::RULE_USER_PASSWORD :
                $user = $rule['user_password'];
                if(isset($user) && !password_verify($attributeValue, $user->password)){
                    $this->addErrorForRule($ruleName, $attribute);
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
            self::RULE_REQUIRED => 'Ovo polje je obavezno',
            self::RULE_EMAIL => 'Ovo polje mora da bude pravi E-mail',
            self::RULE_MIN => 'Minimalna dužina mora biti {min}',
            self::RULE_MAX => 'Maksimalna dužina mora biti {max}',
            self::RULE_MATCH => 'Ovo polje mora da bude isto kao {match}',
            self::RULE_UNIQUE => 'Nalog sa ovim {field} već postoji',
            self::RULE_USER_PASSWORD => 'Sifra koju ste uneli je netacna',
        ];
    }

}