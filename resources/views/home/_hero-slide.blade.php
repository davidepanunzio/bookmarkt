{{--
    Una slide del carosello in home: libro protagonista + copertina.
    Richiede: $libro. Usa $preferitiIds dallo scope del genitore (facoltativo).
--}}
@php $preferitiIds ??= []; @endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
    <div>
        <span class="inline-block text-xs font-semibold uppercase tracking-wide text-green-700 bg-green-100 rounded-full px-3 py-1">
            Ultimo arrivo
        </span>
        <h1 class="font-serif text-4xl sm:text-5xl font-bold text-gray-900 mt-4 leading-tight">
            {{ $libro->title }}
        </h1>
        <p class="text-gray-500 mt-2">{{ $libro->author->name }}</p>
        <p class="mt-4 text-gray-700 leading-relaxed line-clamp-4">
            {{ $libro->description }}
        </p>
        <div class="mt-6 flex flex-wrap items-center gap-4">
            @auth
                <form method="POST" action="{{ route('cart.store', $libro) }}">
                    @csrf
                    <x-primary-button class="px-6 py-3">
                        {{ __('Aggiungi al carrello') }} &middot; {{ number_format($libro->price, 2, ',', '.') }} &euro;
                    </x-primary-button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 rounded-full font-semibold text-sm text-white hover:bg-indigo-700">
                    {{ __('Aggiungi al carrello') }} &middot; {{ number_format($libro->price, 2, ',', '.') }} &euro;
                </a>
            @endauth
            <a href="{{ route('books.show', $libro) }}" class="text-sm font-semibold text-indigo-600 underline">
                Scopri di più
            </a>
        </div>
    </div>

    <div class="group relative overflow-hidden rounded-lg shadow-sm transition-shadow duration-300 hover:shadow-lg w-full max-w-sm mx-auto">
        <a href="{{ route('books.show', $libro) }}">
            <x-book-cover :book="$libro" class="w-full transition-transform duration-300 group-hover:scale-105" />
        </a>

        <div class="absolute inset-x-0 bottom-0 flex items-center justify-center gap-2 p-3
                    opacity-0 translate-y-2 transition-all duration-300
                    group-hover:opacity-100 group-hover:translate-y-0">
            <x-wishlist-button :book="$libro" :preferiti-ids="$preferitiIds" class="w-10 h-10 bg-white shadow-md hover:bg-gray-100" />
            <x-cart-button :book="$libro" class="w-10 h-10 bg-white shadow-md hover:bg-gray-100" />
        </div>
    </div>
</div>
