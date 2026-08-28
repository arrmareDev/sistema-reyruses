<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Categoría creada con éxito', 'category' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return response()->json(['message' => 'Categoría actualizada con éxito', 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        // La FK tiene onDelete('cascade') — si dejamos borrar una categoría con
        // productos, se llevaría esos productos en cascada sin avisar. Lo bloqueamos.
        if ($category->products_count > 0) {
            return response()->json([
                'message' => "No se puede eliminar: tiene {$category->products_count} producto(s) asignado(s). Muévelos a otra categoría primero.",
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente']);
    }
}
