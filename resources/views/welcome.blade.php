@extends('layouts.mylayout')

@section('content')
<div class="flex bg-gray-50">
    <!-- Main content -->
    <div class="flex-1 overflow-auto">
        <!-- Hero Section - Full screen height with centered content -->
        <section class="min-h-screen flex items-center justify-center p-8 bg-gradient-to-br from-white via-indigo-50 to-indigo-100">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-8">
                    <span class="text-xs tracking-[0.5em] text-indigo-600/80 font-medium">LUXURY EYEWEAR</span>
                </div>
                <h1 class="text-6xl md:text-8xl font-bold text-gray-900 mb-12">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-800 to-gray-900">LUXNEWYORK</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-700 mb-12 font-light max-w-2xl mx-auto leading-relaxed">
                    Where <span class="font-medium text-indigo-700">precision craftsmanship</span> meets visionary design.<br>
                    Iconic sunglasses for those who define the future.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    <a href="/products"
                       class="inline-flex items-center justify-center bg-gradient-to-r from-indigo-900 to-black text-white text-lg font-medium rounded-full px-10 py-4 shadow-sm hover:shadow-md transition-all duration-300">
                        Explore Collections
                    </a>
                    <a href="#featured"
                       class="inline-flex items-center justify-center border-2 border-gray-300 text-gray-700 text-lg font-medium rounded-full px-10 py-4 hover:border-indigo-500 hover:text-indigo-700 transition-all duration-300">
                        Discover More
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Collections - Spacious layout -->
        <section id="featured" class="py-24 px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Curated Collections</h2>
                    <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=1200&q=80"
                                 alt="Aviators" class="object-cover w-full h-full hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-8 text-center">
                            <span class="text-xs tracking-widest text-indigo-600 font-medium">CLASSIC</span>
                            <h3 class="text-2xl font-bold mt-2 mb-3">Aviator Icons</h3>
                            <p class="text-gray-600">Timeless silhouettes reimagined</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80"
                                 alt="Men's Statement" class="object-cover w-full h-full hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-8 text-center">
                            <span class="text-xs tracking-widest text-indigo-600 font-medium">SIGNATURE</span>
                            <h3 class="text-2xl font-bold mt-2 mb-3">Statement Men</h3>
                            <p class="text-gray-600">Bold designs for the modern man</p>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80"
                                 alt="Women's Luxury" class="object-cover w-full h-full hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-8 text-center">
                            <span class="text-xs tracking-widest text-indigo-600 font-medium">ELEGANCE</span>
                            <h3 class="text-2xl font-bold mt-2 mb-3">Luxury Women</h3>
                            <p class="text-gray-600">Sophistication in every detail</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Value Propositions - Spacious layout -->
        <section class="py-24 px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">The LUXNEWYORK Difference</h2>
                    <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                    <div class="bg-white border border-gray-200 rounded-xl p-10 text-center shadow-sm">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Global Shipping</h3>
                        <p class="text-gray-600">Complimentary express delivery on all orders over $200 with real-time tracking</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-10 text-center shadow-sm">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 15.536c-1.171 1.952-3.07 1.952-4.242 0-1.172-1.953-1.172-5.119 0-7.072 1.171-1.952 3.07-1.952 4.242 0"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10.5h4m-4 3h4m9-1.5a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Italian Craftsmanship</h3>
                        <p class="text-gray-600">Handmade in Italy using premium acetate and optical-grade lenses</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-10 text-center shadow-sm">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-10 h-10 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-4">Personalized Service</h3>
                        <p class="text-gray-600">24/7 concierge support and virtual try-on consultations</p>
                    </div>
                </div>
            </div>
        </section>
        @livewire('newsletter-form')
        <!-- Newsletter CTA - Spacious layout -->
        <!-- <section class="py-32 px-8 bg-gradient-to-r from-indigo-900 to-black text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6">Join Our World</h2>
                <div class="w-24 h-1 bg-indigo-400 mx-auto mb-12"></div>
                <p class="text-xl text-indigo-200 mb-12">
                    Subscribe for exclusive early access to new collections and private events.
                </p>
                <div class="max-w-md mx-auto flex flex-col sm:flex-row gap-4">
                    <input type="email" placeholder="Your email address" class="flex-grow px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button class="bg-white text-indigo-900 font-semibold px-8 py-4 rounded-full hover:bg-indigo-100 transition duration-300">
                        Subscribe
                    </button>
                </div>
            </div>
        </section> -->
    </div>
</div>
@endsection