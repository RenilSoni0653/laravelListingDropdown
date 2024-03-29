@extends('layouts.app')

@section('content')
@section('title','Payment Details')
    @php $stripe_key = 'pk_test_51Iuxp6SGCTPXtTRvn3lkvujdL88eClG8R5Bj96ElGMgJ3LnkdxpvHRNPqoMSlqMnyEAgnaJR68YQYyHLE0l9Q6nQ00FAgflSOy'; @endphp
    
    <div class="container" style="margin-top:10%;margin-bottom:10%;width:550px;border-radius:10px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- <div class="">
                    <p>You will be charged Rs 100</p>
                </div> -->

                <div class="card">
                    <form action="{{route('checkout.credit-card')}}"  method="post" id="payment-form">
                        @csrf                    
                        <div class="form-group">
                            <div class="card-header">
                                <label for="card-element"> Enter your credit card information </label>
                            </div>

                            <div class="card-body">
                                <div id="card-element">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                    <!-- Used to display form errors. -->
                                <div id="card-errors" role="alert"></div>
                                    <input type="hidden" name="plan" value="" />
                                </div>
                            </div>

                            <div class="card-footer">
                                <button id="card-button" style="border-radius:5px;" type="submit" data-secret="{{ $intent }}" > Pay now</button>
                            </div>
                    </form>
                </div>

                <div class="pr-0 text-right">
                    <a href="{{ url('/home/'.auth()->user()->id) }}">back to home</a>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            },
        };
    
        const stripe = Stripe('{{ $stripe_key }}', { locale: 'en' }); // Create a Stripe client.
        const elements = stripe.elements(); // Create an instance of Elements.
        const cardElement = elements.create('card', { style: style }); // Create an instance of the card Element.
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
    
        cardElement.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.
    
        // Handle real-time validation errors from the card Element.
        cardElement.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    
        // Handle form submission.
        var form = document.getElementById('payment-form');
    
        form.addEventListener('submit', function(event) {
            event.preventDefault();
    
        stripe.handleCardPayment(clientSecret, cardElement, {
                payment_method_data: {
                    //billing_details: { name: cardHolderName.value }
                }
            })
            .then(function(result) {
                console.log(result);
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    return window.location = "/stripe/failedPayment";
                } else {
                    console.log(result);
                    form.submit();
                }
            });
        });
    </script>
@endsection