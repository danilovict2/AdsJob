<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;

abstract class Model{

    protected array $values;

    abstract protected function attributes() : array;

    public static function tableName() : string{
        return static::tableName();
    }

    public function findOne(array $where){
        $tableName = self::tableName();
        $attributes = array_keys($where);
        $whereClause = implode('AND ', array_map(fn($attr) => "$attr = :$attr", $attributes));

    }

    public function save() : void{
        $tableName = self::tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $values = [];
        foreach($attributes as $attribute){
            $values["$attribute"] = $this->values[$attribute] ?? '';
        }
        DB::rawQuery("INSERT INTO $tableName(".implode(',',$attributes).") VALUES (".implode(',',$params).")",
        $values);
    }

}