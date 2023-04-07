<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;

abstract class Model{

    protected array $values;
    protected static string $tableName;

    abstract protected function attributes() : array;

    public static function primaryKey() : string{
        return static::primaryKey();
    }

    public static function findOne(array $where){
        $tableName = static::$tableName;
        $attributes = array_keys($where);
        $whereClause = implode('AND ', array_map(fn($attr) => "$attr = :$attr", $attributes));
        $values = [];
        foreach($where as $key => $value){
            $values["$key"] = $value;
        }
        return DB::rawQuery("SELECT * FROM $tableName WHERE $whereClause LIMIT 1",$values)->fetchObject(static::class);
    }

    public static function exists(string $attribute, string $value) : bool{
        return DB::exists(static::$tableName, $attribute, $value);
    }

    public function create(array $values){
        foreach($values as $key => $value){
            $this->values[$key] = $value;
        }
    }

    public function __set(string $key, string $value) : void{
        $this->values[$key] = $value;
    }

    public function __get(string $key){
        return $this->values[$key];
    }

    public function save() : void{
        $tableName = static::$tableName;
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $values = [];
        foreach($attributes as $attribute){
            $values["$attribute"] = $this->values[$attribute] ?? '';
        }
        DB::rawQuery("INSERT INTO $tableName(".implode(',',$attributes).") VALUES (".implode(',',$params).")",$values);
    }

}