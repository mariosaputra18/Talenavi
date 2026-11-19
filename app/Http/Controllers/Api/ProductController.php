<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:50'],
            'q' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'sort' => ['nullable', 'in:price_asc,price_desc,newest'],
        ]);

        $query = Product::with('category');

        if (!empty($validated['q'])) {
            $query->where('title', 'ilike', '%' . $validated['q'] . '%');
        }

        if (!empty($validated['category'])) {
            $query->whereHas('category', function ($categoryQuery) use ($validated) {
                $categoryQuery->where('slug', $validated['category']);
            });
        }

        $sort = $validated['sort'] ?? 'newest';

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(
            $validated['limit'] ?? 10,
            ['*'],
            'page',
            $validated['page'] ?? 1
        );

        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
        ], 200);
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }

        return response()->json([
            'data' => $product,
        ], 200);
    }
}