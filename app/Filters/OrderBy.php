<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderBy
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('orderBy')) {
            $query->orderBy(
                "products.{$this->request::get('orderBy')}",
                $this->request::get('sort', 'asc')
            );
        }

        return $next($query);
    }
}
