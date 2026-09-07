{{--
    Mostra da 1 a 5 stelle in base a un voto medio (arrotondato).
    Uso: <x-star-rating :rating="$libro->reviews_avg_rating" :count="$libro->reviews_count" />
--}}
@props(['rating' => null, 'count' => 0])

<div {{ $attributes->merge(['class' => 'flex items-center gap-1']) }}>
    @if ($rating)
        @php $arrotondato = round($rating); @endphp
        <span class="text-amber-400 text-sm leading-none">
            @for ($i = 1; $i <= 5; $i++)
                {{ $i <= $arrotondato ? '★' : '☆' }}
            @endfor
        </span>
        <span class="text-xs text-gray-500">
            {{ number_format($rating, 1) }}{{ ! is_null($count) ? " ({$count})" : '' }}
        </span>
    @elseif (! is_null($count))
        <span class="text-xs text-gray-400">Nessuna recensione</span>
    @endif
</div>
