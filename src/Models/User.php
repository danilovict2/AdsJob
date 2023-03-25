<?php declare(strict_types = 1);

namespace AdsJob\Models;

class User extends Model{

    protected string $email, $firstName, $lastName, $password, $confirmPassword;

    public function register(){

    }

    protected function rules() : array{
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'email' => [self::RULE_EMAIL, self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }
}