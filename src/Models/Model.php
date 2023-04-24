<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;
use AdsJob\Traits\HasFactory;

abstract class Model{

    use HasFactory;

    protected array $values;
    protected static string $tableName;

    abstract protected function attributes() : array;

    public static function primaryKey() : string{
        return static::primaryKey();
    }

    public static function tableName() : string{
        return static::$tableName;
    }

    public static function findOne(array $where){
        $tableName = static::$tableName;
        $attributes = array_keys($where);
        $whereClause = implode(' AND ', array_map(fn($attr) => "$attr = :$attr", $attributes));
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

    public function __set(string $key, $value) : void{
        $this->values[$key] = $value;
    }

    public function __get(string $key){
        return $this->values[$key];
    }

    public function __isset(string $property){
        return true;
    }

    public function save() : void{
        $tableName = static::$tableName;
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $values = [];
        foreach($attributes as $attribute){
            $values["$attribute"] = $this->$attribute ?? '';
        }
        DB::rawQuery("INSERT INTO $tableName(".implode(',',$attributes).") VALUES (".implode(',',$params).")",$values);
    }


    public function update(array $values){
        $tableName = static::$tableName;
        $primaryKey = static::primaryKey();
        $setClause = implode(', ', array_map(fn($attr) => "$attr = :$attr", array_keys($values)));
        $values["$primaryKey"] = (int)$this->$primaryKey;
        DB::rawQuery("UPDATE $tableName SET $setClause WHERE $primaryKey = :$primaryKey", $values);
        foreach($values as $key => $value){
            $this->$key = $value;
        }
    }

    public function delete() : void {
        $tableName = static::$tableName;
        $primaryKey = static::primaryKey();
        $values = [$primaryKey => $this->$primaryKey];
        DB::rawQuery("DELETE FROM $tableName WHERE $primaryKey = :$primaryKey", $values);
        foreach($this->attributes() as $attribute) {
            unset($this->$attribute);
        }
    }

}