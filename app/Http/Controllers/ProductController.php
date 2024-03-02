<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $productList = Product::all();
        $data = [
            'status' => 200,
            'products' => $productList
        ];
        return response()->json($data, 200);
    }

    public function detail($id)
    {
        $product = Product::find($id);
        $data = [
            'status' => 200,
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function create(Request $request)
    {
        $product = Product::create($request->all());
        $data = [
            'status' => 200,
            'message' => 'Add Product Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function edit(Request $request, $id)
    {
        // $product->update($request->all());
        $product = Product::find($id);

        $product->name = $request->name;
        $product->quantity = $request->quantity;
        $product->buyingPrice = $request->buyingPrice;
        $product->sellingPrice = $request->sellingPrice;
        $product->description = $request->description;
        $product->imageUrl = $request->imageUrl;

        $product->update();
        $data = [
            'status' => 200,
            'message' => 'Add Update Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }

    public function delete($id)
    {
        $product = Product::find($id)->delete();
        $data = [
            'status' => 200,
            'message' => 'Delete Product Success',
            'product' => $product
        ];
        return response()->json($data, 200);
    }
}
