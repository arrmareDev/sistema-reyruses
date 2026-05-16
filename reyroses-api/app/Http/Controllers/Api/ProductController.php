<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. LEER: Devuelve todos los productos (con el nombre de su categoría)
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        return response()->json($products);
    }

    // 2. CREAR: Guarda un nuevo producto en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            // El precio base lo dejamos opcional/nullable si usaremos los precios por tallo
            'price' => 'nullable|numeric',
            // NUEVO: Validamos los 5 precios por tallo
            'price_50' => 'required|numeric',
            'price_60' => 'required|numeric',
            'price_70' => 'required|numeric',
            'price_80' => 'required|numeric',
            'price_90' => 'required|numeric',
            'stock' => 'required|integer',
            // Validamos que sea imagen y permitimos webp, png, jpg, jpeg (máximo 2MB)
            'image' => 'nullable|image|mimes:webp,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Si el formulario trae una imagen, la guardamos
        if ($request->hasFile('image')) {
            // Se guardará en la carpeta storage/app/public/products
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product = Product::create($data);

        return response()->json(['message' => 'Producto creado con éxito', 'product' => $product], 201);
    }

    // 3. ACTUALIZAR: Modifica un producto existente
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validamos la información que entra
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            // El precio base lo dejamos opcional/nullable
            'price' => 'nullable|numeric',
            // NUEVO: Validamos los 5 precios por tallo
            'price_50' => 'required|numeric',
            'price_60' => 'required|numeric',
            'price_70' => 'required|numeric',
            'price_80' => 'required|numeric',
            'price_90' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:webp,jpeg,png,jpg|max:2048',
        ]);

        // Agarramos todos los datos menos la imagen
        $data = $request->except('image');
        $data['slug'] = Str::slug($request->name);

        // Si el usuario subió una FOTO NUEVA, la guardamos y actualizamos la ruta
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);

        return response()->json(['message' => 'Producto actualizado con éxito', 'product' => $product]);
    }

    // 4. ELIMINAR: Borra un producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
