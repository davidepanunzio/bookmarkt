<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
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
                <a href="{{ route('admin.users.index') }}" class="underline">Utenti</a>
                <span class="font-semibold text-gray-800">{{ $user->name }}</span>
            </nav>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }} &middot; iscritto il {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ $user->isAdmin() ? 'Admin' : 'Cliente' }}
                    </span>
                </div>

                {{-- Cambio ruolo: non disponibile sul proprio account, per evitare di autoescludersi --}}
                @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 flex flex-wrap items-end gap-4">
                        @csrf
                        @method('PUT')
                        <div class="min-w-[160px]">
                            <x-input-label for="role" value="Ruolo" />
                            <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="{{ \App\Models\User::ROLE_USER }}" @selected($user->role === \App\Models\User::ROLE_USER)>Cliente</option>
                                <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected($user->role === \App\Models\User::ROLE_ADMIN)>Admin</option>
                            </select>
                        </div>
                        <x-primary-button>{{ __('Aggiorna ruolo') }}</x-primary-button>
                    </form>
                @else
                    <p class="mt-6 text-sm text-gray-500">Non puoi modificare il ruolo del tuo stesso account.</p>
                @endif
            </div>

            {{-- Ordini effettuati --}}
            <div class="bg-white rounded-lg shadow-sm">
                <h3 class="font-semibold text-gray-900 p-4 pb-0">Ordini ({{ $user->orders->count() }})</h3>
                <div class="divide-y mt-2">
                    @forelse ($user->orders as $ordine)
                        <a href="{{ route('admin.orders.show', $ordine) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <span class="text-gray-700">Ordine #{{ $ordine->id }} &middot; {{ $ordine->created_at->format('d/m/Y') }}</span>
                            <div class="flex items-center gap-3">
                                <x-order-status-badge :status="$ordine->status" />
                                <span class="font-semibold text-gray-900">{{ number_format($ordine->total, 2, ',', '.') }} &euro;</span>
                            </div>
                        </a>
                    @empty
                        <p class="p-4 text-sm text-gray-500">Nessun ordine effettuato.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recensioni scritte --}}
            <div class="bg-white rounded-lg shadow-sm">
                <h3 class="font-semibold text-gray-900 p-4 pb-0">Recensioni ({{ $user->reviews->count() }})</h3>
                <div class="divide-y mt-2">
                    @forelse ($user->reviews as $recensione)
                        <div class="p-4 flex items-start justify-between gap-4">
                            <div>
                                <a href="{{ route('books.show', $recensione->book) }}" class="text-gray-900 font-medium hover:underline">
                                    {{ $recensione->book->title }}
                                </a>
                                <x-star-rating :rating="$recensione->rating" :count="null" class="mt-1" />
                                @if ($recensione->comment)
                                    <p class="text-sm text-gray-600 mt-1">{{ $recensione->comment }}</p>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('reviews.destroy', $recensione) }}"
                                  onsubmit="return confirm('Eliminare questa recensione?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 underline shrink-0">Elimina</button>
                            </form>
                        </div>
                    @empty
                        <p class="p-4 text-sm text-gray-500">Nessuna recensione scritta.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
