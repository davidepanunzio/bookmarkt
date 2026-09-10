<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">
            {{ __('Il tuo carrello') }}
        </h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($cart->items->isEmpty())
                <div class="bg-white rounded-2xl p-6 text-center text-gray-600">
                    Il carrello è vuoto.
                    <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8 items-start">
                    {{-- Righe del carrello --}}
                    <div class="space-y-4">
                        @foreach ($cart->items as $item)
                            <div class="bg-white rounded-2xl p-4 flex items-center gap-4">
                                <x-book-cover :book="$item->book" class="w-16 shrink-0" />

                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('books.show', $item->book) }}" class="font-serif font-semibold text-gray-900 hover:underline">
                                        {{ $item->book->title }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $item->book->author->name }}</p>

                                    <div class="mt-2 flex items-center gap-3">
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                   max="{{ $item->book->stock }}"
                                                   class="w-16 rounded-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <button type="submit" class="text-xs text-indigo-600 underline">Aggiorna</button>
                                        </form>

                                        <form method="POST" action="{{ route('cart.destroy', $item) }}"
                                              onsubmit="return confirm('Rimuovere questo libro dal carrello?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 underline">Rimuovi</button>
                                        </form>
                                    </div>
                                </div>

                                <span class="font-semibold text-gray-900 shrink-0">
                                    {{ number_format($item->subtotale(), 2, ',', '.') }} &euro;
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Riepilogo --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 lg:sticky lg:top-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Riepilogo</h3>
                        <div class="flex items-center justify-between text-sm text-gray-700 py-1">
                            <span>Libri ({{ $cart->items->sum('quantity') }})</span>
                            <span>{{ number_format($cart->totale(), 2, ',', '.') }} &euro;</span>
                        </div>
                        <div class="border-t border-gray-200 mt-3 pt-3 flex items-center justify-between">
                            <span class="font-semibold text-gray-900">Totale</span>
                            <span class="font-serif text-xl font-bold text-gray-900">{{ number_format($cart->totale(), 2, ',', '.') }} &euro;</span>
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="block mt-4">
                            <x-primary-button class="w-full justify-center py-3">
                                {{ __('Procedi al checkout') }}
                            </x-primary-button>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
