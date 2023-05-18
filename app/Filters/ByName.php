<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ByName
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('name')) {
            $query->where('name', 'REGEXP', $this->request::get('name'));
        }

        return $next($query);
    }
}
