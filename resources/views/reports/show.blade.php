<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Segnalazione') }} #{{ $report->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $report->subject }}</h1>
                        <p class="text-sm text-gray-500">
                            Inviata il {{ $report->created_at->format('d/m/Y H:i') }}
                            @if ($report->order)
                                &middot; riferita all'<a href="{{ route('orders.show', $report->order) }}" class="underline">ordine #{{ $report->order->id }}</a>
                            @endif
                        </p>
                    </div>
                    <x-report-status-badge :status="$report->status" />
                </div>

                <p class="mt-6 text-gray-700 whitespace-pre-line">{{ $report->message }}</p>
            </div>

            @if ($report->hasReply())
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                    <p class="text-sm font-medium text-indigo-800 mb-2">
                        Risposta dell'amministratore &middot; {{ $report->replied_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-gray-700 whitespace-pre-line">{{ $report->admin_reply }}</p>
                </div>
            @else
                <p class="text-sm text-gray-500">Nessuna risposta ancora, la tua segnalazione è in coda.</p>
            @endif

            <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 underline">&larr; Torna alle mie segnalazioni</a>
        </div>
    </div>
</x-app-layout>
