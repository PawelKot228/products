<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Filters\ByName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $pipelines = [
            ByName::class,
        ];

        return Pipeline::send(Product::query())
            ->through($pipelines)
            ->thenReturn()
            ->paginate();
    }

    public function store(Request $request)
    {
    }

    public function show(Product $product)
    {
    }

    public function update(Request $request, Product $product)
    {
    }

    public function destroy(Product $product)
    {
    }
}
