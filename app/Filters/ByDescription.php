<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ByDescription
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('description')) {
            $query->where('products.name', 'LIKE', "%{$this->request::get('description')}%");
        }

        return $next($query);
    }
}
