<?php declare(strict_types = 1);

namespace AdsJob\Traits;
use AdsJob\Database\DB;
use AdsJob\Models\Model;

Trait HasFactory{

    protected function hasMany(string $relatedModel) : array{
        $leftTable = static::tableName();
        $rightTable = $relatedModel::tableName();

        $leftTablePK = static::primaryKey();
        $rightTablePK = $relatedModel::primaryKey();

        return DB::rawQuery("SELECT * FROM $leftTable INNER JOIN $rightTable ON $leftTable.$leftTablePK = $rightTable.$rightTablePK")->fetchAll();
    }

    protected function hasOne(string $relatedModel) : array{
        return $this->hasMany($relatedModel)[0];
    }
}