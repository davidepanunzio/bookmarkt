<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione ordini') }}
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
                <span class="font-semibold text-gray-800">Ordini</span>
            </nav>

            {{-- Filtro per stato --}}
            <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-end">
                <div class="min-w-[200px]">
                    <x-input-label for="status" value="Filtra per stato" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tutti</option>
                        @foreach (\App\Models\Order::STATUS_LABELS as $valore => $etichetta)
                            <option value="{{ $valore }}" @selected(request('status') === $valore)>{{ $etichetta }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button>{{ __('Filtra') }}</x-primary-button>
                @if (request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 underline">Azzera filtro</a>
                @endif
            </form>

            <div class="bg-white rounded-lg shadow-sm divide-y">
                @forelse ($ordini as $ordine)
                    <a href="{{ route('admin.orders.show', $ordine) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">Ordine #{{ $ordine->id }} &middot; {{ $ordine->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $ordine->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <x-order-status-badge :status="$ordine->status" />
                            <span class="font-semibold text-gray-900 w-20 text-right">{{ number_format($ordine->total, 2, ',', '.') }} &euro;</span>
                        </div>
                    </a>
                @empty
                    <p class="p-4 text-gray-600">Nessun ordine trovato.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $ordini->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
