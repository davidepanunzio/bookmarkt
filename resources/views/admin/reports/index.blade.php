<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Segnalazioni degli utenti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <nav class="text-sm text-gray-500 space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
                <span class="font-semibold text-gray-800">Segnalazioni</span>
            </nav>

            {{-- Filtro per stato --}}
            <form method="GET" action="{{ route('admin.reports.index') }}" class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-end">
                <div class="min-w-[200px]">
                    <x-input-label for="status" value="Filtra per stato" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tutte</option>
                        @foreach (\App\Models\Report::STATUS_LABELS as $valore => $etichetta)
                            <option value="{{ $valore }}" @selected(request('status') === $valore)>{{ $etichetta }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>{{ __('Filtra') }}</x-primary-button>
                @if (request('status'))
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-500 underline mb-2">Azzera filtro</a>
                @endif
            </form>

            <div class="bg-white rounded-2xl divide-y">
                @forelse ($segnalazioni as $segnalazione)
                    <a href="{{ route('admin.reports.show', $segnalazione) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $segnalazione->subject }}</p>
                            <p class="text-sm text-gray-500">
                                di {{ $segnalazione->user->name }} &middot; {{ $segnalazione->created_at->format('d/m/Y H:i') }}
                                @if ($segnalazione->order_id)
                                    &middot; ordine #{{ $segnalazione->order_id }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if ($segnalazione->hasReply())
                                <span class="text-xs text-indigo-600">Risposta inviata</span>
                            @endif
                            <x-report-status-badge :status="$segnalazione->status" />
                        </div>
                    </a>
                @empty
                    <p class="p-4 text-gray-600">Nessuna segnalazione ricevuta.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $segnalazioni->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
