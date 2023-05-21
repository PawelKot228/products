<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ByPriceStart
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('priceStart')) {
            $priceStart = $this->request::get('priceStart');

            $query->where('prices.price', '>=', $priceStart);
            $query->with('prices', fn(HasMany $query) => $query->where('price', '>=', $priceStart));
        }

        return $next($query);
    }
}
