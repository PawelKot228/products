<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPriceRequest;
use App\Models\Product;
use App\Models\Price;
use Illuminate\Http\JsonResponse;

class ProductPriceController extends Controller
{
    public function store(ProductPriceRequest $request, Product $product): JsonResponse
    {
        try {
            $productPrice = $product->prices()->save(new Price($request->validated()));
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json($productPrice, 201);
    }

    public function update(ProductPriceRequest $request, $product, $productPrice): JsonResponse
    {
        $productPrice = Price::where('product_id', $product)->findOrFail($productPrice);

        try {
            $productPrice->fill($request->validated())->save();
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json(status: 204);
    }

    public function destroy($product, $productPrice): JsonResponse
    {
        $productPrice = Price::where('product_id', $product)->findOrFail($productPrice);

        try {
            $productPrice->delete();
        } catch (\Exception) {
            return response()->json('Something went wrong', 400);
        }

        return response()->json(status: 204);
    }
}
