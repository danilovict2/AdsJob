<?php declare(strict_types = 1);

namespace AdsJob\Models;

class User extends Model{

    protected static string $tableName = "user";

    public function __construct(
        array $values = []
    ){
        $this->values = $values;
    }

    protected function attributes() : array{
        return ['firstName', 'lastName', 'password', 'email'];
    }

    public function save() : void{
        $this->values['password'] = password_hash($this->values['password'], PASSWORD_DEFAULT);
        parent::save();
    }

}