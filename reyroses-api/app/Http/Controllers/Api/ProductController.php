<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        return response()->json($products);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tipo' => 'required|in:Nacional,Exportacion',
            'name' => 'required|string|max:255',

            'price' => 'nullable|numeric',


            'price_50' => 'required|numeric',
            'stock_50' => 'required|integer',

            'price_60' => 'required|numeric',
            'stock_60' => 'required|integer',

            'price_70' => 'required|numeric',
            'stock_70' => 'required|integer',

            'price_80' => 'required|numeric',
            'stock_80' => 'required|integer',

            'price_90' => 'required|numeric',
            'stock_90' => 'required|integer',


            'image' => 'nullable|image|mimes:webp,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);


        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product = Product::create($data);

        return response()->json(['message' => 'Producto creado con éxito', 'product' => $product], 201);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);


        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'tipo' => 'required|in:Nacional,Exportacion',
            'name' => 'required|string|max:255',

            'price' => 'nullable|numeric',


            'price_50' => 'required|numeric',
            'stock_50' => 'required|integer',

            'price_60' => 'required|numeric',
            'stock_60' => 'required|integer',

            'price_70' => 'required|numeric',
            'stock_70' => 'required|integer',

            'price_80' => 'required|numeric',
            'stock_80' => 'required|integer',

            'price_90' => 'required|numeric',
            'stock_90' => 'required|integer',

            'image' => 'nullable|image|mimes:webp,jpeg,png,jpg|max:2048',
        ]);


        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);

        return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $product]);
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
