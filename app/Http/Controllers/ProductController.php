<?php

namespace App\Http\Controllers;

use App\Filters\ByCurrency;
use App\Filters\ByDescription;
use App\Filters\ByName;
use App\Filters\ByPriceEnd;
use App\Filters\ByPriceStart;
use App\Filters\OrderBy;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Pipeline;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(ProductIndexRequest $request): LengthAwarePaginator
    {
        $query = Product::with('prices')
            ->select('products.*')
            ->leftJoin('prices', 'prices.product_id', '=', 'products.id')
            ->groupBy('products.id');

        $query = Pipeline::send($query)
            ->through([
                ByName::class,
                ByDescription::class,
                ByCurrency::class,
                ByPriceStart::class,
                ByPriceEnd::class,
                OrderBy::class,
            ])->thenReturn();

        return $query->paginate($request->get('limit', 20));
    }

    public function store(ProductRequest $request): JsonResponse
    {
        try {
            $product = Product::create($request->validated());
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json($product, 201);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('prices');

        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try {
            $product->fill($request->validated())->save();
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json(status: 204);
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json(status: 204);
    }
}
