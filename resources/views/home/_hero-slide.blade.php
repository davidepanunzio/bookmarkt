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
            @auth
                @php $nePreferiti = in_array($libro->id, $preferitiIds); @endphp
                <form method="POST"
                      action="{{ $nePreferiti ? route('wishlist.destroy', $libro) : route('wishlist.store', $libro) }}">
                    @csrf
                    @if ($nePreferiti) @method('DELETE') @endif
                    <button type="submit"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md hover:bg-gray-100 {{ $nePreferiti ? 'text-red-500' : 'text-gray-700' }}"
                            title="{{ $nePreferiti ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}">
                        <svg viewBox="0 0 24 24" fill="{{ $nePreferiti ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md hover:bg-gray-100 text-gray-700"
                   title="Accedi per aggiungere ai preferiti">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </a>
            @endauth

            @if ($libro->isDisponibile())
                @auth
                    <form method="POST" action="{{ route('cart.store', $libro) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md text-gray-700 hover:bg-gray-100 hover:text-indigo-600"
                                title="Aggiungi al carrello">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white shadow-md text-gray-700 hover:bg-gray-100 hover:text-indigo-600"
                       title="Accedi per aggiungere al carrello">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </a>
                @endauth
            @endif
        </div>
    </div>
</div>
