<section class="py-32 px-8 bg-gradient-to-r from-indigo-900 to-black text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-4xl font-bold mb-6">Join Our World</h2>
        <div class="w-24 h-1 bg-indigo-400 mx-auto mb-12"></div>
        <p class="text-xl text-indigo-200 mb-12">
            Subscribe for exclusive early access to new collections and private events.
        </p>

        <div class="max-w-md mx-auto">
            <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-4">
                <input type="email"
                       wire:model.defer="email"
                       placeholder="Your email address"
                       class="flex-grow px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       required>
                <button type="submit"
                    class="bg-indigo-600 text-white font-semibold px-8 py-4 rounded-full hover:bg-indigo-700 transition duration-300">
                    Subscribe
                </button>
            </form>
            @error('email')
                <div class="text-red-300 text-sm text-left mt-2">{{ $message }}</div>
            @enderror
            @if($success)
                <div class="text-green-300 text-base mt-4">{{ $success }}</div>
            @endif
        </div>
    </div>
</section>
