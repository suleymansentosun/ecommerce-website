@extends('layout')

@section('title', 'Checkout')

@section('content')

    <div class="container">

        @if (session('success_message'))
        <div class="spacer"></div>
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="spacer"></div>
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h1 class="checkout-heading stylish-heading">Checkout</h1>
        <div class="checkout-section">
            <div>
                <form id="payment-form">
                    @csrf
                    
                    <h2>Billing Details</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        @if(auth()->user())
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        @else
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="province">State / Province/ Region</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="half-form">                        
                        <div class="form-group">
                            <label for="postalcode">Postal Code</label>
                            <input type="text" class="form-control" id="postalcode" name="postalcode" value="{{ old('postalcode') }}" required>
                        </div>
                        {{-- <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="" required>
                        </div> --}}
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Payment Details</h2>

                    {{-- <div class="form-group">
                        <label for="name_on_card">Name on Card</label>
                        <input type="text" class="form-control" id="name_on_card" name="name_on_card" value="" required>
                    </div> --}}

                    <div class="form-group">
                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element"><!--Stripe.js injects the Card Element--></div>
                        <p id="card-error" role="alert"></p>
                        <p class="alert-success result-message hidden">
                            Thank you, your payment has been successfully accepted.
                        </p>
                    </div>

                    {{-- <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="">
                    </div> --}}

                    {{-- <div class="form-group">
                        <label for="cc-number">Credit Card Number</label>
                        <input type="text" class="form-control" id="cc-number" name="cc-number" value="">
                    </div>

                    <div class="half-form">
                        <div class="form-group">
                            <label for="expiry">Expiry</label>
                            <input type="text" class="form-control" id="expiry" name="expiry" placeholder="MM/DD">
                        </div>
                        <div class="form-group">
                            <label for="cvc">CVC Code</label>
                            <input type="text" class="form-control" id="cvc" name="cvc" value="">
                        </div>
                    </div> <!-- end half-form --> --}}

                    <div class="spacer"></div>

                    <button type="submit" id="submit" class="full-width button-primary">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Complete Order</span>
                    </button>

                    <a id="continue-shopping" class="button-primary full-width text-center make-block hidden" href="{{ route('shop.index') }}">Continue Shopping</a>
                </form>
            </div>



            <div class="checkout-table-container">
                <h2>Your Order</h2>

                <div class="checkout-table">
                    @foreach (Cart::content() as $item)
                    <div class="checkout-table-row">
                        <div class="checkout-table-row-left">
                            <img src="{{ productImage($item->model->image) }}" alt="item" class="checkout-table-img">
                            <div class="checkout-item-details">
                                <div class="checkout-table-item">{{ $item->model->name }}</div>
                                <div class="checkout-table-description">{{ $item->model->details }}</div>
                                <div class="checkout-table-price">{{ $item->model->presentPrice() }}</div>
                            </div>
                        </div> <!-- end checkout-table -->

                        <div class="checkout-table-row-right">
                            <div class="checkout-table-quantity">{{ $item->qty }}</div>
                        </div>
                    </div> <!-- end checkout-table-row -->
                    @endforeach                    
                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-totals-left">
                        Subtotal <br>
                        @if(session()->has('coupon'))
                            Discount ({{ session('coupon')['name'] }}) : 
                            <form action="{{ route('coupon.destroy') }}" method="POST" style="display:inline">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button type="submit" style="font-size: 14px">Remove</button>
                            </form>
                            <br>
                            <hr>
                            New subtotal <br>        
                        @endif
                        Tax <br>
                        <span class="checkout-totals-total">Total</span>

                    </div>

                    <div class="checkout-totals-right">
                        {{ presentPrice(Cart::subtotal()) }} <br>
                        @if(session()->has('coupon'))
                            {{ presentPrice($discount) }} <br>
                            <hr>
                            {{ presentPrice($newSubtotal) }} <br>
                        @endif
                        {{ presentPrice($newTax) }} <br>
                        <span class="checkout-totals-total">{{ presentPrice($newTotal) }}</span>

                    </div>
                </div> <!-- end checkout-totals -->

                @if(!session()->has('coupon'))
                    <a href="#" class="have-code">Have a Code?</a>

                    <div class="have-code-container">
                        <form action="{{ route('coupon.store') }}" method="POST">
                            @csrf
                            <input type="text" name="coupon_code" id="coupon_code">
                            <button type="submit" class="button button-plain">Apply</button>
                        </form>
                    </div> <!-- end have-code-container -->
                @endif

            </div>

        </div> <!-- end checkout-section -->
    </div>

@endsection

@section('extra-js')
    <script>
        (function(){
            var stripe = Stripe("pk_test_51L1VZnFdmTxg0e5xoZpYpow4wtmB9nqWyQ2qFGll6JVMDIi15GvYSMbn5f2fYrZe3kvFy2CYbrW9TBghNcqA9pQh00FhwqXjlo");

            document.getElementById('submit').disabled = true;

            fetch("/checkout/createPaymentIntent")
                .then(response => response.json())
                .then(function(data) {
                    var elements = stripe.elements();
                    var style = {
                        base: {
                            color: "#32325d",
                            fontFamily: 'Arial, sans-serif',
                            fontSmoothing: "antialiased",
                            fontSize: "16px",
                            "::placeholder": {
                                color: "#32325d"
                            }
                        },
                        invalid: {
                            fontFamily: 'Arial, sans-serif',
                            color: "#fa755a",
                            iconColor: "#fa755a"
                        }
                    };
                    var card = elements.create("card", { style: style, hidePostalCode: true });
                    card.mount("#card-element");

                    card.on("change", function (event) {
                        document.getElementById('submit').disabled = event.empty;
                        document.getElementById("card-error").textContent = event.error ? event.error.message : "";
                    });

                    var form = document.getElementById("payment-form");
                    form.addEventListener("submit", function(event) {
                        event.preventDefault();
                        payWithCard(stripe, card, data.clientSecret);
                    });
                });     
            
            var payWithCard = function(stripe, card, clientSecret) {
                loading(true);
                stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: document.getElementById('name').value,
                            email: document.getElementById('email').value,
                            address: {
                                line1: document.getElementById('address').value,
                                city: document.getElementById('city').value,
                                state: document.getElementById('province').value,
                                postal_code: document.getElementById('postalcode').value,
                                country: 'BR',
                            },
                        },
                    }
                }).then(function(result) {
                    if (result.error) {
                        showError(result.error.message);
                    } else {
                        orderComplete(result.paymentIntent.id);
                        
                        let xhr = new XMLHttpRequest();
                        xhr.open("GET", "/confirmation/emptyCart");
                        xhr.send();
                    }
                });
            };

            var orderComplete = function(paymentIntentId) {
            loading(false);
            document.querySelector(".result-message").classList.remove("hidden");
            document.querySelector("button").classList.add("hidden");
            document.querySelector("#continue-shopping").classList.remove("hidden");
            };

            var showError = function(errorMsgText) {
            loading(false);
            var errorMsg = document.querySelector("#card-error");
            errorMsg.textContent = errorMsgText;
            setTimeout(function() {
                errorMsg.textContent = "";
            }, 4000);
            };

            var loading = function(isLoading) {
            if (isLoading) {
                document.querySelector("button").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("button").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
            };
        })();
    </script>
@endsection
