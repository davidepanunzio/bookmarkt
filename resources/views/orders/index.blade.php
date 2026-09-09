<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">
            {{ __('I miei ordini') }}
        </h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($ordini->isEmpty())
                <div class="bg-white rounded-2xl p-6 text-center text-gray-600">
                    Non hai ancora effettuato ordini.
                    <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                </div>
            @else
                <div class="bg-white rounded-2xl divide-y divide-gray-100" data-reveal>
                    @foreach ($ordini as $ordine)
                        <a href="{{ route('orders.show', $ordine) }}" class="p-5 flex items-center justify-between hover:bg-gray-50 first:rounded-t-2xl last:rounded-b-2xl">
                            <div>
                                <p class="font-serif font-semibold text-gray-900">Ordine #{{ $ordine->id }}</p>
                                <p class="text-sm text-gray-500">{{ $ordine->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <x-order-status-badge :status="$ordine->status" />
                                <span class="font-semibold text-gray-900">{{ number_format($ordine->total, 2, ',', '.') }} &euro;</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $ordini->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
