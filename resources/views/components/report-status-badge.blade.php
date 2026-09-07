{{--
    Badge colorato per lo stato di una segnalazione.
    Uso: <x-report-status-badge :status="$report->status" />
--}}
@props(['status'])

@php
    $colori = [
        'nuovo' => 'bg-red-100 text-red-800',
        'in_lavorazione' => 'bg-yellow-100 text-yellow-800',
        'risolto' => 'bg-green-100 text-green-800',
    ];
    $classe = $colori[$status] ?? 'bg-gray-100 text-gray-800';
    $etichetta = \App\Models\Report::STATUS_LABELS[$status] ?? $status;
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-medium px-2 py-1 rounded-full $classe"]) }}>
    {{ $etichetta }}
</span>
