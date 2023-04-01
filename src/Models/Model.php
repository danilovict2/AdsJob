<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;

abstract class Model{

    protected array $values;

    abstract protected function attributes() : array;

    public function __construct(
        private DB $db,
    ){

    }

    public static function tableName() : string{
        return static::tableName();
    }

    public function save() : void{
        $tableName = self::tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $values = [];
        foreach($attributes as $attribute){
            $values["$attribute"] = $this->values[$attribute] ?? '';
        }
        $this->db->rawQuery("INSERT INTO $tableName(".implode(',',$attributes).") VALUES (".implode(',',$params).")",
        $values);
    }

}