<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ByPriceEnd
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('priceEnd')) {
            $priceEnd = $this->request::get('priceEnd');

            $query->where('prices.price', '<=', $priceEnd);
            $query->with('prices', fn(HasMany $query) => $query->where('price', '<=', $priceEnd));
        }

        return $next($query);
    }
}
