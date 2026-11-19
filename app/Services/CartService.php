<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function index(int $userId)
    {
        $cart = CartItem::where('user_id', $userId)
            ->with('product.category')
            ->get();

        $total = $cart->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return [
            'data' => $cart,
            'total_price' => $total,
        ];
    }

    public function store(int $userId, array $validated)
    {
        return DB::transaction(function () use ($userId, $validated) {
            $product = Product::where('id', $validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $cartItem = CartItem::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            $newQuantity = ($cartItem ? $cartItem->quantity : 0)
                + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                return [
                    'error' => 'Insufficient stock for product: ' . $product->title,
                ];
            }

            if ($cartItem) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cartItem = CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                ]);
            }

            return $cartItem;
        });
    }

    public function update(int $userId, int $id, array $validated)
    {
        return DB::transaction(function () use ($userId, $id, $validated) {
            $cartItem = CartItem::where('user_id', $userId)
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$cartItem) {
                return ['error' => 'Cart item not found.'];
            }

            $product = Product::where('id', $cartItem->product_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['quantity'] > $product->stock) {
                return [
                    'error' => 'Insufficient stock for product: ' . $product->title,
                ];
            }

            $cartItem->update([
                'quantity' => $validated['quantity'],
            ]);

            return $cartItem->load('product');
        });
    }

    public function destroy(int $userId, int $id)
    {
        $cartItem = CartItem::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return ['error' => 'Cart item not found.'];
        }

        $cartItem->delete();

        return true;
    }
}
