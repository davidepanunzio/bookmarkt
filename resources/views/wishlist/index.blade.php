<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">
            {{ __('I miei preferiti') }}
        </h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($libri->isEmpty())
                <div class="bg-white rounded-2xl p-6 text-center text-gray-600">
                    Non hai ancora salvato nessun libro tra i preferiti.
                    <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10" data-reveal>
                    @foreach ($libri as $libro)
                        <div class="group relative flex flex-col">
                            <form method="POST" action="{{ route('wishlist.destroy', $libro) }}" class="absolute top-3 right-3 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/90 text-red-500 hover:text-red-700 shadow" title="Rimuovi dai preferiti">
                                    &hearts;
                                </button>
                            </form>

                            <a href="{{ route('books.show', $libro) }}" class="flex flex-col flex-1">
                                <div class="overflow-hidden rounded-lg shadow-sm transition-shadow duration-300 group-hover:shadow-lg">
                                    <x-book-cover :book="$libro" class="w-full transition-transform duration-300 group-hover:scale-105" />
                                </div>
                                <span class="mt-3 text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                                    {{ $libro->category->name }}
                                </span>
                                <h3 class="mt-1 font-serif font-semibold text-gray-900 transition-colors duration-300 group-hover:text-indigo-600">{{ $libro->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $libro->author->name }}</p>
                                <span class="mt-2 font-bold text-gray-900">{{ number_format($libro->price, 2, ',', '.') }} &euro;</span>
                            </a>

                            @if ($libro->isDisponibile())
                                <form method="POST" action="{{ route('cart.store', $libro) }}" class="mt-3">
                                    @csrf
                                    <x-primary-button class="w-full justify-center">{{ __('Aggiungi al carrello') }}</x-primary-button>
                                </form>
                            @else
                                <span class="mt-3 text-xs text-red-700 bg-red-100 px-2 py-1 rounded-full text-center block">Esaurito</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $libri->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
