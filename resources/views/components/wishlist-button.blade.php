{{--
    Pulsante per aggiungere/rimuovere un libro dai preferiti, riusato in card, hero e classifica best seller.
    Se l'utente non è autenticato diventa un link al login invece di un form: evita che, dopo il login,
    Laravel provi a "tornare" su un URL che accetta solo POST (vedi anche <x-cart-button>).
    Richiede: :book. Opzionale: :preferiti-ids (array di id nei preferiti dell'utente, assente = nessuno).
--}}
@props(['book', 'preferitiIds' => []])

@php
    $nePreferiti = in_array($book->id, $preferitiIds);
@endphp

@auth
    <form method="POST" action="{{ $nePreferiti ? route('wishlist.destroy', $book) : route('wishlist.store', $book) }}">
        @csrf
        @if ($nePreferiti) @method('DELETE') @endif
        <button type="submit"
                {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full '.($nePreferiti ? 'text-red-500' : 'text-gray-700')]) }}
                title="{{ $nePreferiti ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}">
            <svg viewBox="0 0 24 24" fill="{{ $nePreferiti ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
        </button>
    </form>
@else
    <a href="{{ route('login') }}"
       {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full text-gray-700']) }}
       title="Accedi per aggiungere ai preferiti">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
        </svg>
    </a>
@endauth
