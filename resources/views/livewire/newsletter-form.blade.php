<section class="py-32 px-8 bg-gradient-to-r from-indigo-900 to-black text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-6xl font-bold mb-4">Join Our Newsletter</h2>
        <div class="w-24 h-1 bg-indigo-400 mx-auto mb-12"></div>
        <p class="text-sm text-indigo-200 mb-12">
            Subscribe for exclusive early access to new collections and private events.
        </p>

        <div class="max-w-md mx-auto">
            <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3 bg-white/70 p-3 rounded-xl shadow border border-gray-100">
                <input type="email"
                    wire:model.defer="email"
                    placeholder="Your email address"
                    class="flex-grow px-4 py-2.5 rounded-full text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-base shadow-sm"
                    required>
                <button type="submit"
                    class="bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-full shadow hover:bg-indigo-700 hover:shadow-md focus:ring-2 focus:ring-indigo-500 transition text-base">
                    Subscribe
                </button>
            </form>
            @error('email')
                <div class="text-red-500/90 text-sm mt-2 pl-2">{{ $message }}</div>
            @enderror
            @if($success)
                <div class="text-green-600/90 text-base mt-3 pl-2 font-medium">{{ $success }}</div>
            @endif
        </div>
    </div>
</section>
