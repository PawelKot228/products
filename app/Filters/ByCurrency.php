<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ByCurrency
{
    public function __construct(protected \Request $request)
    {
        //
    }

    public function handle(Builder $query, \Closure $next)
    {
        if ($this->request::has('currency')) {
            $currency = $this->request::get('currency');

            $query->where('prices.currency', $currency);
            $query->with('prices', fn(HasMany $query) => $query->where('currency', $currency));
        }

        return $next($query);
    }
}
