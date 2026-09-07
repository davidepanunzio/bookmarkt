{{--
    Badge colorato per lo stato di un ordine.
    Uso: <x-order-status-badge :status="$order->status" />
--}}
@props(['status'])

@php
    // Colore associato a ciascuno stato (classi Tailwind)
    $colori = [
        'in_attesa' => 'bg-yellow-100 text-yellow-800',
        'pagato' => 'bg-blue-100 text-blue-800',
        'spedito' => 'bg-indigo-100 text-indigo-800',
        'consegnato' => 'bg-green-100 text-green-800',
        'annullato' => 'bg-red-100 text-red-800',
    ];
    $classe = $colori[$status] ?? 'bg-gray-100 text-gray-800';
    $etichetta = \App\Models\Order::STATUS_LABELS[$status] ?? $status;
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-medium px-2 py-1 rounded-full $classe"]) }}>
    {{ $etichetta }}
</span>
