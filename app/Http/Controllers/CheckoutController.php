<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cart::instance('default')->count() == 0) {
            return redirect()->route('shop.index');
        }

        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }

        return view('checkout')
                    ->with('discount', $this->getNumbers()->get('discount'))
                    ->with('newSubtotal', $this->getNumbers()->get('newSubtotal'))
                    ->with('newTax', $this->getNumbers()->get('newTax'))
                    ->with('newTotal', $this->getNumbers()->get('newTotal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Create a paymentintent
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createPaymentIntent()
    {
        \Stripe\Stripe::setApiKey('sk_test_51L1VZnFdmTxg0e5xAdNRGLTN3Gy78j2jB7G836PR89HmNfC10RRfERS7w5UKVRWJy77XcOXHTTETrh0j5maRABhW0088zLHrlQ');

        $contents = Cart::content()->map(function ($item) {
            return $item->model->slug . ', ' . $item->qty;
        })->values()->toJson();

        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => intval(str_replace(',', '', $this->getNumbers()->get('newTotal'))),
                'currency' => 'usd',
                "metadata" => [
                    "contents" => $contents,
                    "quantity" => Cart::instance('default')->count(),
                    "discount" => collect(session('coupon'))->toJson(),
                ]
            ]);
            
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
    
            return json_encode($output);
        } catch (Error $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function getNumbers()
    {
        $tax = config('cart.tax') / 100;
        $discount = session('coupon')['discount'] ?? 0;
        $newSubtotal = ((float)str_replace(',', '', Cart::subtotal()) - $discount);
        $newTax = $newSubtotal * $tax;
        $newTotal = $newSubtotal * (1 + $tax);

        return collect([
            'tax' => $tax,
            'discount' => $discount,
            'newSubtotal' => $newSubtotal,
            'newTax' => $newTax,
            'newTotal' => $newTotal
        ]);
    }
}
