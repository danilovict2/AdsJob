<?php

declare(strict_types=1);

namespace AdsJob\Traits;

use AdsJob\Database\DB;
use AdsJob\Models\Model;

trait HasFactory{

    protected function hasMany(string $relatedModel, string $leftTableKey, string $rightTableKey): array{
        $leftTable = static::tableName();
        $rightTable = $relatedModel::tableName();

        $result = DB::rawQuery("SELECT $rightTable.* FROM $rightTable 
        INNER JOIN $leftTable ON $leftTable.$leftTableKey = $rightTable.$rightTableKey 
        WHERE $leftTable.$leftTableKey = " . $this->$leftTableKey ?? '')->fetchAll();

        $relatedModels = [];
        foreach ($result as $row) {
            $relatedModelInstance = new $relatedModel;
            $relatedModelInstance->create($row);
            $relatedModels[] = $relatedModelInstance;
        }
        return $relatedModels;
    }

    protected function hasOne(string $relatedModel, string $leftTableKey, string $rightTableKey): ?Model{
        $results = $this->hasMany($relatedModel, $leftTableKey, $rightTableKey);
        return empty($results) ? null : $results[0];
    }
}
