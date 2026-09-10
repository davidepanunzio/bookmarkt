<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    <p class="font-semibold">Controlla i dati inseriti:</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $errore)
                            <li>{{ $errore }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start">
                @csrf

                {{-- Dati di spedizione e pagamento --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Dati account</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Nome</p>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Email</p>
                                <p class="text-gray-900 font-medium">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Spedizione</h3>

                        <x-input-label for="shipping_address" value="Indirizzo di spedizione" />
                        <textarea id="shipping_address" name="shipping_address" rows="2" required
                                  placeholder="Via, numero civico, CAP, città, provincia"
                                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('shipping_address', $ultimoIndirizzo) }}</textarea>
                        <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />

                        <div class="mt-4">
                            <x-input-label for="note" value="Nota per il corriere (facoltativa)" />
                            <textarea id="note" name="note" rows="2" placeholder="Es. citofonare, lasciare al vicino..."
                                      class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Pagamento</h3>
                        <div class="space-y-3">
                            @foreach (\App\Models\Order::PAYMENT_LABELS as $valore => $etichetta)
                                <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-3 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="payment_method" value="{{ $valore }}"
                                           @checked(old('payment_method', \App\Models\Order::PAYMENT_CONTRASSEGNO) === $valore)
                                           class="text-indigo-600 focus:ring-indigo-500" required>
                                    <span class="text-sm text-gray-800">{{ $etichetta }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                    </div>
                </div>

                {{-- Riepilogo ordine --}}
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 lg:sticky lg:top-6">
                    <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Il tuo ordine</h3>

                    <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                        @foreach ($cart->items as $item)
                            <div class="flex items-center gap-3">
                                <x-book-cover :book="$item->book" class="w-12 shrink-0" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->book->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} &times; {{ number_format($item->book->price, 2, ',', '.') }} &euro;</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 shrink-0">
                                    {{ number_format($item->subtotale(), 2, ',', '.') }} &euro;
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4 flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Totale</span>
                        <span class="font-serif text-xl font-bold text-gray-900">{{ number_format($cart->totale(), 2, ',', '.') }} &euro;</span>
                    </div>

                    <x-primary-button class="w-full justify-center py-3 mt-4">
                        {{ __('Conferma ordine') }} &middot; {{ number_format($cart->totale(), 2, ',', '.') }} &euro;
                    </x-primary-button>

                    <a href="{{ route('cart.index') }}" class="block text-center text-sm text-gray-500 underline mt-3">
                        &larr; Torna al carrello
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
