{{--
    Card di un libro, riusata sia nel catalogo che nella home.
    Richiede: $libro. Opzionale: $preferitiIds (array di id nei preferiti dell'utente).
--}}
@php $preferitiIds ??= []; @endphp

<div class="group relative flex flex-col">
    <div class="relative overflow-hidden rounded-lg shadow-sm transition-shadow duration-300 group-hover:shadow-lg">
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

    <a href="{{ route('books.show', $libro) }}" class="flex flex-col flex-1">
        <div class="mt-3 flex items-center justify-between gap-2">
            <span class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                {{ $libro->category->name }}
            </span>
            @unless ($libro->isDisponibile())
                <span class="text-xs text-red-700 bg-red-100 px-2 py-0.5 rounded-full shrink-0">Esaurito</span>
            @endunless
        </div>
        <h3 class="mt-1 font-serif font-semibold text-gray-900 transition-colors duration-300 group-hover:text-indigo-600">{{ $libro->title }}</h3>
        <p class="text-sm text-gray-500">{{ $libro->author->name }}</p>
        <div class="mt-2 flex items-center justify-between">
            <span class="font-bold text-gray-900">{{ number_format($libro->price, 2, ',', '.') }} &euro;</span>
            <x-star-rating :rating="$libro->reviews_avg_rating" :count="$libro->reviews_count" />
        </div>
    </a>
</div>
