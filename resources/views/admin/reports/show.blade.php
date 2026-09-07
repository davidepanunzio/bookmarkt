<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Segnalazione') }} #{{ $report->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <nav class="text-sm text-gray-500 space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
                <a href="{{ route('admin.reports.index') }}" class="underline">Segnalazioni</a>
                <span class="font-semibold text-gray-800">#{{ $report->id }}</span>
            </nav>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $report->subject }}</h1>
                        <p class="text-sm text-gray-500">
                            Da {{ $report->user->name }} ({{ $report->user->email }}) &middot; {{ $report->created_at->format('d/m/Y H:i') }}
                            @if ($report->order)
                                &middot; riferita all'ordine <a href="{{ route('admin.orders.show', $report->order) }}" class="underline">#{{ $report->order->id }}</a>
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
                        Risposta inviata il {{ $report->replied_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-gray-700 whitespace-pre-line">{{ $report->admin_reply }}</p>
                </div>
            @endif

            {{-- Form per rispondere e cambiare lo stato della segnalazione --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-3">
                    {{ $report->hasReply() ? 'Modifica risposta e stato' : 'Rispondi e aggiorna stato' }}
                </h3>
                <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="admin_reply" value="Risposta per l'utente (facoltativa)" />
                        <textarea id="admin_reply" name="admin_reply" rows="4"
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('admin_reply', $report->admin_reply) }}</textarea>
                        <x-input-error :messages="$errors->get('admin_reply')" class="mt-2" />
                    </div>

                    <div class="flex flex-wrap items-end gap-4">
                        <div class="min-w-[200px]">
                            <x-input-label for="status" value="Stato" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach (\App\Models\Report::STATUS_LABELS as $valore => $etichetta)
                                    <option value="{{ $valore }}" @selected($report->status === $valore)>{{ $etichetta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-primary-button>{{ __('Salva') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
