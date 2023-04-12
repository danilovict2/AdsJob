<?php declare(strict_types = 1);

namespace AdsJob\Traits;
use AdsJob\Database\DB;
use AdsJob\Models\Model;

Trait HasFactory{

    protected function hasMany(string $relatedModel, string $leftTableKey, string $rightTableKey) : array{
        $leftTable = static::tableName();
        $rightTable = $relatedModel::tableName();
        return DB::rawQuery("SELECT $rightTable.* FROM $rightTable INNER JOIN $leftTable ON $leftTable.$leftTableKey = $rightTable.$rightTableKey")->fetchAll();
    }

    protected function hasOne(string $relatedModel, string $leftTableKey, string $rightTableKey) : array{
        return $this->hasMany($relatedModel, $leftTableKey, $rightTableKey)[0];
    }
}