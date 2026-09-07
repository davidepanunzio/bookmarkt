<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard amministratore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Link rapidi alle altre sezioni di gestione --}}
            <nav class="text-sm text-gray-500 space-x-3">
                <span class="font-semibold text-gray-800">Dashboard</span>
                <a href="{{ route('books.index') }}" class="underline">Libri</a>
                <a href="{{ route('admin.categories.index') }}" class="underline">Categorie</a>
                <a href="{{ route('admin.authors.index') }}" class="underline">Autori</a>
                <a href="{{ route('admin.orders.index') }}" class="underline">Ordini</a>
                <a href="{{ route('admin.reviews.index') }}" class="underline">Recensioni</a>
                <a href="{{ route('admin.reports.index') }}" class="underline">
                    Segnalazioni
                    @if ($segnalazioniNuove > 0)
                        <span class="ms-1 inline-flex items-center justify-center text-xs bg-red-600 text-white rounded-full w-5 h-5">{{ $segnalazioniNuove }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="underline">Utenti</a>
            </nav>

            {{-- Statistiche generali, in forma di "card" --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Libri in catalogo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $numeroLibri }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Categorie</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $numeroCategorie }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Autori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $numeroAutori }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Clienti registrati</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $numeroClienti }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Ordini totali</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $numeroOrdini }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-sm text-gray-500">Fatturato (ordini non annullati)</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($fatturatoTotale, 2, ',', '.') }} &euro;</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Suddivisione ordini per stato --}}
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <h3 class="font-semibold text-gray-900 mb-3">Ordini per stato</h3>
                    @foreach (\App\Models\Order::STATUS_LABELS as $valore => $etichetta)
                        <div class="flex items-center justify-between py-1">
                            <x-order-status-badge :status="$valore" />
                            <span class="font-medium text-gray-700">{{ $ordiniPerStato[$valore] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Libri più venduti --}}
                <div class="bg-white rounded-lg shadow-sm p-5 lg:col-span-2">
                    <h3 class="font-semibold text-gray-900 mb-3">Libri più venduti</h3>
                    @forelse ($libriPiuVenduti as $riga)
                        <div class="flex items-center justify-between py-1">
                            <a href="{{ route('books.show', $riga->book) }}" class="text-gray-700 hover:underline">
                                {{ $riga->book->title }}
                            </a>
                            <span class="font-medium text-gray-900">{{ $riga->venduti }} venduti</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Nessuna vendita registrata.</p>
                    @endforelse
                </div>
            </div>

            {{-- Ultimi ordini ricevuti --}}
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-5 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Ultimi ordini</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 underline">Vedi tutti</a>
                </div>
                <div class="divide-y">
                    @forelse ($ultimiOrdini as $ordine)
                        <a href="{{ route('admin.orders.show', $ordine) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">Ordine #{{ $ordine->id }} &middot; {{ $ordine->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $ordine->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <x-order-status-badge :status="$ordine->status" />
                                <span class="font-semibold text-gray-900">{{ number_format($ordine->total, 2, ',', '.') }} &euro;</span>
                            </div>
                        </a>
                    @empty
                        <p class="p-4 text-gray-500 text-sm">Nessun ordine ricevuto.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
