<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ordine') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Effettuato il {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <div class="mt-1"><x-order-status-badge :status="$order->status" /></div>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ number_format($order->total, 2, ',', '.') }} &euro;</span>
            </div>

            <div class="bg-white rounded-lg shadow-sm divide-y">
                @foreach ($order->items as $item)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->book->title }}</p>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} &times; {{ number_format($item->price, 2, ',', '.') }} &euro;</p>
                        </div>
                        <span class="font-semibold text-gray-900">{{ number_format($item->subtotale(), 2, ',', '.') }} &euro;</span>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 underline">&larr; Torna ai miei ordini</a>
        </div>
    </div>
</x-app-layout>
