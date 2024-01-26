<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function checkOut(){
        $user = auth()->user();
        $cartItems = $user->cartItems;

        $totalPrice = 0;
        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem->quantity * $cartItem->product->price;
            $cartItem->delete();
        }

        return response()->json(['total_price' => $totalPrice]);
    }
}
