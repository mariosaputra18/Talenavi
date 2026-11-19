<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET: Menampilkan cart milik user
    public function index(Request $request, CartService $cartService)
    {
        return response()->json(
            $cartService->index($request->user()->id),
            200
        );
    }

    // POST: Menambahkan produk ke cart
    public function store(Request $request, CartService $cartService)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $result = $cartService->store(
            $request->user()->id,
            $validated
        );

        if (is_array($result) && isset($result['error'])) {
            $status = $result['error'] === 'Cart item not found.'
                ? 404
                : 422;

            return response()->json([
                'message' => $result['error'],
            ], $status);
        }

        return response()->json([
            'message' => 'Product added to cart successfully.',
            'data' => $result->load('product'),
        ], 201);
    }

    // PUT: Mengubah quantity cart
    public function update(Request $request, $id, CartService $cartService)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $result = $cartService->update(
            $request->user()->id,
            (int) $id,
            $validated
        );

        if (is_array($result) && isset($result['error'])) {
            $status = $result['error'] === 'Cart item not found.'
                ? 404
                : 422;

            return response()->json([
                'message' => $result['error'],
            ], $status);
        }

        return response()->json([
            'message' => 'Cart quantity updated successfully.',
            'data' => $result,
        ], 200);
    }

    // DELETE: Menghapus item cart
    public function destroy(Request $request, $id, CartService $cartService)
    {
        $result = $cartService->destroy(
            $request->user()->id,
            (int) $id
        );

        if (is_array($result) && isset($result['error'])) {
            return response()->json([
                'message' => $result['error'],
            ], 404);
        }

        return response()->json([
            'message' => 'Cart item deleted successfully.',
        ], 200);
    }
}
