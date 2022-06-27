<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Group;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = 9;
        $groups = Group::all();

        if (request()->query('group')) {
            $products = Product::with('groups')->whereHas('groups', function ($query) {
                $query->where('slug', request()->query('group'));
            });
            $groupName = optional( $groups->where('slug', request()->query('group'))->first() )->name;
        } else {
            $products = Product::where('featured', true);
            $groupName = 'Featured';
        }

        if (request()->query('sort') == 'low_high') {
            $products = $products->orderBy('price')->paginate($pagination);
        } else if (request()->query('sort') == 'high_low') {
            $products = $products->orderBy('price', 'desc')->paginate($pagination);
        } else {
            $products = $products->paginate($pagination);
        }

        return view('shop')
                    ->with('products', $products)
                    ->with('groups', $groups)
                    ->with('groupName', $groupName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)->mightAlsoLike()->get();

        return view('product')
                    ->with('product', $product)
                    ->with('mightAlsoLike', $mightAlsoLike);
    }
}
