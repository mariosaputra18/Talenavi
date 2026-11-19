<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request, CheckoutService $checkoutService)
    {
        $result = $checkoutService->checkout($request->user()->id);

        if (is_array($result) && isset($result['error'])) {
            return response()->json([
                'message' => $result['error'],
            ], 422);
        }

        return response()->json([
            'message' => 'Checkout successful.',
            'data' => $result,
        ], 201);
    }
}
