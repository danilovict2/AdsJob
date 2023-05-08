<?php declare(strict_types = 1);

namespace AdsJob\Models;
use AdsJob\Database\DB;
use AdsJob\Traits\HasFactory;

abstract class Model implements \JsonSerializable{

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

    public function create(array $values) : void{
        foreach($values as $key => $value){
            $this->values[$key] = $value;
        }
    }

    public function __set(string $key, $value) : void{
        $this->values[$key] = $value;
    }

    public function __get(string $key){
        $tableName = static::tableName();
        $primaryKey = static::primaryKey();
        if(isset($this->values[$primaryKey])){
            return DB::rawQuery("SELECT $key FROM $tableName WHERE $primaryKey = :$primaryKey", 
            [$primaryKey => $this->values[$primaryKey]])->fetchColumn();
        }
    }

    public function __isset(string $property) : bool{
        return true;
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
        $this->values[static::primaryKey()] = DB::lastInsertId();
    }


    public function update(array $values) : void{
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

    public static function all() : array {
        $tableName = static::$tableName;
        return DB::rawQuery("SELECT * FROM $tableName")->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public function jsonSerialize() : mixed{
        return [
            'values' => $this->values
        ];
    }

}