<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Requests\ProductRequest;
use CodeShopping\Models\Product;
use CodeShopping\Http\Resources\ProductResource;
use CodeShopping\Common\OnlyTrashed;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ProductController extends Controller
{
    use OnlyTrashed;

    public function index(Request $request)
    {
        $query = Product::query();
        $query = $this->onlyTrashedIfRequested($request, $query);
        $products = $query->paginate(15);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all() + ['slug' => 'teste']);
        $product->refresh();
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product); // return  $product
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->all());
        $product->save();
        //return $product; //return new ProductResource($product);
        return response()->json([], 204); //Não exibe dados atualizados
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([], 204);
    }

    public function restore(Product $product)
    {
        $product->restore();
        return response()->json([], 204);
    }
}
