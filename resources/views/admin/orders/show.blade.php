<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
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

            <nav class="text-sm text-gray-500 space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" class="underline">Ordini</a>
                <span class="font-semibold text-gray-800">#{{ $order->id }}</span>
            </nav>

            <div class="bg-white rounded-2xl p-6 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="font-medium text-gray-900">Cliente: {{ $order->user->name }} ({{ $order->user->email }})</p>
                    <p class="text-sm text-gray-500">Effettuato il {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <div class="mt-1"><x-order-status-badge :status="$order->status" /></div>
                </div>
                <span class="text-2xl font-bold text-gray-900">{{ number_format($order->total, 2, ',', '.') }} &euro;</span>
            </div>

            {{-- Form per cambiare lo stato dell'ordine --}}
            <div class="bg-white rounded-2xl p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Aggiorna stato</h3>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex flex-wrap items-end gap-4">
                    @csrf
                    @method('PUT')
                    <div class="min-w-[200px]">
                        <select name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach (\App\Models\Order::STATUS_LABELS as $valore => $etichetta)
                                <option value="{{ $valore }}" @selected($order->status === $valore)>{{ $etichetta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button>{{ __('Salva stato') }}</x-primary-button>
                </form>
            </div>

            <div class="bg-white rounded-2xl divide-y">
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

            <div class="bg-white rounded-2xl p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Indirizzo di spedizione</p>
                    <p class="text-gray-900 font-medium whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Pagamento</p>
                    <p class="text-gray-900 font-medium">{{ $order->paymentMethodLabel() }}</p>
                </div>
                @if ($order->note)
                    <div class="sm:col-span-2">
                        <p class="text-gray-500">Nota del cliente</p>
                        <p class="text-gray-900">{{ $order->note }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
