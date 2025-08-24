
<div>
    <div class="photobooth-body min-h-screen flex items-center justify-center p-8 bg-gradient-to-br from-pink-200 to-pink-100">
        <div class="photobooth-container w-full max-w-3xl mx-auto text-center rounded-2xl border border-white/20 bg-white/90 backdrop-blur shadow-2xl p-10 space-y-6">
            <h1 class="font-black text-3xl md:text-4xl text-rose-600">Laravel Livewire Photobooth</h1>
            <div>
                <h2 class="text-rose-800/80 text-xl md:text-2xl font-semibold">Choose Your Strip Layout</h2>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($layouts as $key => $label)
                        <button wire:click="selectLayout('{{ $key }}')"
                                wire:loading.attr="disabled" wire:target="selectLayout"
                                class="rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-semibold py-6 shadow-lg hover:shadow-xl transition-transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed">
                            <span>{{ $label }}</span>
                            <span wire:loading wire:target="selectLayout" class="ml-2">…</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
