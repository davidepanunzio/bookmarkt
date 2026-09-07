<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Il tuo carrello') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($cart->items->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 text-center text-gray-600">
                    Il carrello è vuoto.
                    <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm divide-y">
                    @foreach ($cart->items as $item)
                        <div class="p-4 flex items-center justify-between gap-4">
                            <div>
                                <a href="{{ route('books.show', $item->book) }}" class="font-medium text-gray-900 hover:underline">
                                    {{ $item->book->title }}
                                </a>
                                <p class="text-sm text-gray-500">{{ number_format($item->book->price, 2, ',', '.') }} &euro; cad.</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                           max="{{ $item->book->stock }}"
                                           class="w-16 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <button type="submit" class="text-sm text-indigo-600 underline">Aggiorna</button>
                                </form>

                                <span class="font-semibold text-gray-900 w-20 text-right">
                                    {{ number_format($item->subtotale(), 2, ',', '.') }} &euro;
                                </span>

                                <form method="POST" action="{{ route('cart.destroy', $item) }}"
                                      onsubmit="return confirm('Rimuovere questo libro dal carrello?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 underline">Rimuovi</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <span class="text-lg font-semibold text-gray-900">Totale</span>
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($cart->totale(), 2, ',', '.') }} &euro;</span>
                </div>

                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <x-primary-button class="w-full justify-center py-3">
                        {{ __('Conferma ordine') }}
                    </x-primary-button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
