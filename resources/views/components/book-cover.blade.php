{{--
    Copertina di un libro: mostra l'immagine caricata se presente, altrimenti un blocco
    di colore con autore e titolo in overlay (come nei mockup, utile perché il catalogo
    demo non ha foto di copertine reali).
    Il rapporto 2:3 (tipico di una copertina) è già incluso di default: basta passare
    solo la larghezza, es. <x-book-cover :book="$libro" class="w-full" />
--}}
@props(['book'])

@php
    // Tavolozza fissa: il colore è scelto in modo deterministico dall'id del libro,
    // così lo stesso libro mostra sempre lo stesso colore.
    $palette = [
        ['bg' => 'bg-green-800', 'text' => 'text-white'],
        ['bg' => 'bg-stone-200', 'text' => 'text-stone-900'],
        ['bg' => 'bg-neutral-500', 'text' => 'text-white'],
        ['bg' => 'bg-indigo-600', 'text' => 'text-white'],
        ['bg' => 'bg-gray-900', 'text' => 'text-white'],
        ['bg' => 'bg-rose-200', 'text' => 'text-rose-900'],
        ['bg' => 'bg-lime-700', 'text' => 'text-white'],
        ['bg' => 'bg-amber-800', 'text' => 'text-white'],
    ];
    $colore = $palette[$book->id % count($palette)];
@endphp

@if ($book->cover_image)
    <img src="{{ $book->coverUrl() }}" alt="Copertina di {{ $book->title }}"
         {{ $attributes->merge(['class' => 'aspect-[2/3] object-cover rounded-lg']) }}>
@else
    <div {{ $attributes->merge(['class' => "aspect-[2/3] {$colore['bg']} {$colore['text']} rounded-lg p-4 flex flex-col justify-between"]) }}>
        <span class="text-xs uppercase tracking-wide opacity-75">{{ $book->author->name }}</span>
        <span class="font-serif font-bold text-lg leading-tight">{{ $book->title }}</span>
    </div>
@endif
