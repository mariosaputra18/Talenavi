<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function checkout(int $userId)
    {
        return DB::transaction(function () use ($userId) {
            $cartItems = CartItem::where('user_id', $userId)
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                return ['error' => 'Your cart is empty.'];
            }

            $total = 0;
            $orderItems = [];

            foreach ($cartItems as $cartItem) {
                $product = Product::where('id', $cartItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product || $cartItem->quantity > $product->stock) {
                    return [
                        'error' => 'Insufficient stock for product: '
                            . ($product ? $product->title : 'Unknown product'),
                    ];
                }

                $total += $product->price * $cartItem->quantity;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $total,
                'status' => 'completed',
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);
            }

            CartItem::where('user_id', $userId)->delete();

            return $order->load('items.product');
        });
    }
}
