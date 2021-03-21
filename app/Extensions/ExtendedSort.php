<?php

namespace App\Extensions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Concerns\BuildsQueries;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
trait ExtendedSort {

    use BuildsQueries;

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Throwable
     */
    protected static function sorting(Request $request) {
        $index = $request->sort + 1;
        \DB::transaction(function () use ($request, &$index) {
            self::where('sort', '>=', $request->sort)
                ->whereKeyNot($request->id)
                ->orderBy('sort')
                ->each(function ($x) use (&$index){
                    $x->sort = $index;
                    $x->save();
                    $index ++;
                });
        });
    }

    protected static function bootExtendedSort() {

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('sort', 'asc');
        });

        // after_create
        self::created(function ($attributes) {
            \DB::transaction(function () use ($attributes) {
                $max = self::max('sort');
                $model = self::where('sort', 0)->first();
                if (!$model) return;
                $model->sort = $max + 1;
                $model->save();
            });
        });

        // after_delete
        self::deleted(function () {
           \DB::transaction(function () {
               $count = 1;
               self::orderBy('sort')
                   ->each(function ($x) use (&$count){
                       $x->sort = $count;
                       $x->save();
                       $count ++;
                   });
           });
        });

    }
}
