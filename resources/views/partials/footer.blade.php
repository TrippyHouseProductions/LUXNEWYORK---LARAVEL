<footer class="bg-white border-t border-gray-200 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <!-- Branding and copyright -->
            <div class="flex flex-col items-center md:items-start mb-8 md:mb-0">
                <div class="text-2xl font-bold text-gray-900 mb-2">LUXNEWYORK</div>
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} All rights reserved. Crafted with passion.
                </p>
                <!-- Admin Only Link -->
                <a href="/admin/login" class="mt-4 flex items-center text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Administrators Only
                </a>
            </div>

            <!-- Links -->
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Shop</h3>
                    <div class="space-y-3">
                        <a href="/products" class="text-gray-500 hover:text-indigo-700 transition-colors block">All Products</a>
                        <a href="/collections" class="text-gray-500 hover:text-indigo-700 transition-colors block">Collections</a>
                        <a href="/new-arrivals" class="text-gray-500 hover:text-indigo-700 transition-colors block">New Arrivals</a>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Company</h3>
                    <div class="space-y-3">
                        <a href="/about" class="text-gray-500 hover:text-indigo-700 transition-colors block">About Us</a>
                        <a href="/blog" class="text-gray-500 hover:text-indigo-700 transition-colors block">Blog</a>
                        <a href="/careers" class="text-gray-500 hover:text-indigo-700 transition-colors block">Careers</a>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Support</h3>
                    <div class="space-y-3">
                        <a href="/contact" class="text-gray-500 hover:text-indigo-700 transition-colors block">Contact</a>
                        <a href="/faq" class="text-gray-500 hover:text-indigo-700 transition-colors block">FAQs</a>
                        <a href="/shipping" class="text-gray-500 hover:text-indigo-700 transition-colors block">Shipping Policy</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom row -->
        <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center">
            <div class="flex space-x-6 mb-4 md:mb-0">
                <a href="#" class="text-gray-400 hover:text-indigo-700 transition-colors">
                    <span class="sr-only">Facebook</span>
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-indigo-700 transition-colors">
                    <span class="sr-only">Instagram</span>
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-indigo-700 transition-colors">
                    <span class="sr-only">Twitter</span>
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-indigo-700 transition-colors">
                    <span class="sr-only">Pinterest</span>
                    <i class="fab fa-pinterest"></i>
                </a>
            </div>
            <div class="flex space-x-6 text-sm">
                <a href="/privacy" class="text-gray-500 hover:text-indigo-700 transition-colors">Privacy Policy</a>
                <a href="/terms" class="text-gray-500 hover:text-indigo-700 transition-colors">Terms of Service</a>
                <a href="/returns" class="text-gray-500 hover:text-indigo-700 transition-colors">Returns</a>
            </div>
        </div>
    </div>
</footer>