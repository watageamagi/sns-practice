<?php

namespace App\Extensions\Relations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Pagination
{
    public function scopePagination(Builder $query, Request $request, $count=20) {

        $paginate = $request->query('paginate', false);
        if ($paginate) {
            return $query->paginate($paginate);
        }
        return $query->paginate($count);
    }

}
