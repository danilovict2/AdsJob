<?php declare(strict_types = 1);

namespace AdsJob\Models;

class User extends Model{

    public function __construct(
        \AdsJob\Database\DB $db,
        array $values
    ){
        parent::__construct($db);
        $this->values = $values;
    }

    public static function tableName() : string{
        return 'user';
    }

    protected function attributes() : array{
        return ['firstName', 'lastName', 'password', 'email'];
    }

    public function save() : void{
        $this->values['password'] = password_hash($this->values['password'], PASSWORD_DEFAULT);
        parent::save();
    }

   
}