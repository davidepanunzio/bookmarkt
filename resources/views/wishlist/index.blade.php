<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('I miei preferiti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($libri->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 text-center text-gray-600">
                    Non hai ancora salvato nessun libro tra i preferiti.
                    <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($libri as $libro)
                        <div class="relative bg-white rounded-lg shadow-sm hover:shadow-md transition p-5 flex flex-col">
                            <form method="POST" action="{{ route('wishlist.destroy', $libro) }}" class="absolute top-3 right-3 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xl text-red-500 hover:text-red-700" title="Rimuovi dai preferiti">
                                    &hearts;
                                </button>
                            </form>

                            <a href="{{ route('books.show', $libro) }}" class="flex flex-col flex-1">
                                <img src="{{ $libro->coverUrl() }}" alt="Copertina di {{ $libro->title }}"
                                     class="w-full h-48 object-cover rounded-md mb-3 bg-gray-100">
                                <span class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                                    {{ $libro->category->name }}
                                </span>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">{{ $libro->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $libro->author->name }}</p>
                                <span class="mt-auto pt-4 text-lg font-bold text-gray-900">{{ number_format($libro->price, 2, ',', '.') }} &euro;</span>
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

                <div class="mt-6">
                    {{ $libri->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
