<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;

abstract class Model{

    protected array $values;
    protected static string $tableName;

    abstract protected function attributes() : array;

    public function findOne(array $where){
        $tableName = static::$tableName;
        $attributes = array_keys($where);
        $whereClause = implode('AND ', array_map(fn($attr) => "$attr = :$attr", $attributes));

    }

    public static function exists(string $attribute, string $value) : bool{
        return DB::exists(static::$tableName, $attribute, $value);
    }

    public function save() : void{
        $tableName = static::$tableName;
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