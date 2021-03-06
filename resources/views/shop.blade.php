@extends('layout')

@section('title', 'Products')

@section('extra-css')

@endsection

@section('content')

    <div class="breadcrumbs">
        <div class="container">
            <a href="/">Home</a>
            <i class="fa fa-chevron-right breadcrumb-separator"></i>
            <span>Shop</span>
        </div>
    </div> <!-- end breadcrumbs -->

    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach ($groups as $group)
                    <li class="{{ setActiveGroup($group->slug) }}"><a href="{{ route('shop.index', ['group' => $group->slug]) }}">{{ $group->name }}</a></li>
                @endforeach
            </ul>

            {{-- <h3>By Price</h3>
            <ul>
                <li><a href="#">$0 - $700</a></li>
                <li><a href="#">$700 - $2500</a></li>
                <li><a href="#">$2500+</a></li>
            </ul> --}}
        </div> <!-- end sidebar -->
        <div>
            <div class="products-header">
                <h1 class="stylish-heading">{{ $groupName }}</h1>
                <div>
                    <strong>Price:</strong>
                    <a href="{{ route('shop.index', ['group' => request()->query('group'), 'sort' => 'low_high']) }}">Low to High</a>
                    <a href="{{ route('shop.index', ['group' => request()->query('group'), 'sort' => 'high_low']) }}">High to Low</a>
                </div>
            </div>
            <div class="products text-center">
                @forelse($products as $product)
                    <div class="product">
                        <a href="{{ route('shop.show', ['product' => $product->slug]) }}"><img src="{{ productImage($product->image) }}" alt="product"></a>
                        <a href="{{ route('shop.show', ['product' => $product->slug]) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">{{ $product->presentPrice() }}</div>
                    </div>                    
                @empty
                    <div style="text-align: left">No items found</div>
                @endforelse

            </div> <!-- end products -->
            <div class="spacer">
                {{ $products->appends(request()->input())->links() }}
            </div>
        </div>
    </div>


@endsection
