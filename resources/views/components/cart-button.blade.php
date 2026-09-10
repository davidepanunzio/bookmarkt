{{--
    Pulsante per aggiungere un libro al carrello (quantità 1), riusato in card, hero e classifica best seller.
    Non si mostra se il libro è esaurito. Per gli ospiti diventa un link al login: vedi la nota
    in <x-wishlist-button> sul perché non usiamo un form anche per loro.
    Richiede: $book.
--}}
@props(['book'])

@if ($book->isDisponibile())
    @auth
        <form method="POST" action="{{ route('cart.store', $book) }}">
            @csrf
            <button type="submit"
                    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full text-gray-700 hover:text-indigo-600']) }}
                    title="Aggiungi al carrello">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </button>
        </form>
    @else
        <a href="{{ route('login') }}"
           {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full text-gray-700 hover:text-indigo-600']) }}
           title="Accedi per aggiungere al carrello">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
        </a>
    @endauth
@endif
