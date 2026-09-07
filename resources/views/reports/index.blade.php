<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Le mie segnalazioni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('reports.create') }}">
                    <x-primary-button>{{ __('+ Nuova segnalazione') }}</x-primary-button>
                </a>
            </div>

            @if ($segnalazioni->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-6 text-center text-gray-600">
                    Non hai ancora inviato nessuna segnalazione.
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm divide-y">
                    @foreach ($segnalazioni as $segnalazione)
                        <a href="{{ route('reports.show', $segnalazione) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $segnalazione->subject }}</p>
                                <p class="text-sm text-gray-500">{{ $segnalazione->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if ($segnalazione->hasReply())
                                    <span class="text-xs text-indigo-600">Risposta ricevuta</span>
                                @endif
                                <x-report-status-badge :status="$segnalazione->status" />
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $segnalazioni->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
