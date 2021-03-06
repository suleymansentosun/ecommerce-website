<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Gloudemans\Shoppingcart\Facades\Cart;

class CouponController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $coupon = Coupon::where('code', $request->input('coupon_code'))->first();

        if (!$coupon) {
            return redirect()->route('checkout.index')->withErrors('Invalid coupon code. Please try again.');
        }

        session(['coupon' => [
            'name' => $coupon->code,
            'discount' => $coupon->discount( (float)str_replace(',', '', Cart::subtotal()) ),
        ]]);

        return redirect()->route('checkout.index')->with('success_message', 'Coupon has been applied!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        session()->forget('coupon');

        return redirect()->route('checkout.index')->with('success_message', 'Coupon has been removed.');
    }
}
