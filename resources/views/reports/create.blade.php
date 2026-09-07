<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contatta gli amministratori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-6">
                    Hai riscontrato un problema con il sito, un ordine o un libro? Scrivici e un amministratore
                    esaminerà la tua segnalazione al più presto.
                </p>

                <form method="POST" action="{{ route('reports.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="subject" value="Oggetto" />
                        <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full"
                                      value="{{ old('subject') }}" required autofocus />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="message" value="Messaggio" />
                        <textarea id="message" name="message" rows="6" required
                                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('message') }}</textarea>
                        <x-input-error :messages="$errors->get('message')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="order_id" value="Collega a un ordine (facoltativo)" />
                        @if ($ordini->isNotEmpty())
                            <select id="order_id" name="order_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Nessun ordine --</option>
                                @foreach ($ordini as $ordine)
                                    <option value="{{ $ordine->id }}" @selected(old('order_id') == $ordine->id)>
                                        Ordine #{{ $ordine->id }} del {{ $ordine->created_at->format('d/m/Y') }} ({{ number_format($ordine->total, 2, ',', '.') }} &euro;)
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('order_id')" class="mt-2" />
                        @else
                            <p class="mt-1 text-sm text-gray-400">
                                Non hai ancora effettuato ordini, quindi non puoi collegarne uno a questa segnalazione.
                            </p>
                        @endif
                    </div>

                    <x-primary-button>{{ __('Invia segnalazione') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
