@extends('layouts.mylayout')

@section('content')
    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
            <div class="w-24 h-1 bg-indigo-600 mx-auto mb-6"></div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
            <form id="checkout-form">
                <div class="space-y-6">
                    <!-- Payment Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                <input type="text" name="fake_payment_info" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       placeholder="4242 4242 4242 4242" required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="MM/YY" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                                    <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                           placeholder="123" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full btn-primary py-3 px-4 text-lg font-medium">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>

            <!-- Result Message -->
            <div id="checkout-result" class="mt-6 text-center text-lg hidden"></div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const resultDiv = document.getElementById('checkout-result');

        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = checkoutForm.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

            const token = localStorage.getItem('api_token');
            axios.post('/api/orders', {
                fake_payment_info: this.fake_payment_info.value
            }, {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                resultDiv.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle mr-2"></i>Order placed successfully!</span>';
                resultDiv.classList.remove('hidden');
                checkoutForm.reset();
            })
            .catch(error => {
                resultDiv.innerHTML = '<span class="text-red-600"><i class="fas fa-exclamation-circle mr-2"></i>Order failed. Please try again.</span>';
                resultDiv.classList.remove('hidden');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'Place Order';
            });
        });
    });
    </script>

    <style>
        :root {
            --primary-color: #202124;
            --secondary-color: #34a853;
            --accent-color: #fbbc04;
            --text-color: #202124;
            --background-color: #ffffff;
        }
        
        .btn-primary {
            position: relative;
            display: inline-block;
            padding: 0.75rem 2rem;
            border: 1px solid var(--primary-color);
            background-color: var(--primary-color);
            color: white;
            overflow: hidden;
            z-index: 1;
            border-radius: 9999px;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(255, 255, 255);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 0.4s ease;
            border-radius: 9999px;
            z-index: -1;
        }

        .btn-primary:hover::before {
            transform: scaleX(1);
        }

        .btn-primary:hover {
            color: var(--primary-color);
        }
    </style>
@endsection