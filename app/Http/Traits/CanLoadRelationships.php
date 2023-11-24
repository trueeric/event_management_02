<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationships
{
    public function loadRelationships(Model | QueryBuilder | EloquentBuilder $for,
        ?array $relations = null
    ): Model | QueryBuilder | EloquentBuilder {
        $relations = $relations ?? $this->relations ?? [];

        // 有relation的值時，要列入該relation來查詢
        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }
        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }

}
