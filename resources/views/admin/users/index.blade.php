<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Utenti registrati') }}
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
                <span class="font-semibold text-gray-800">Utenti</span>
            </nav>

            {{-- Ricerca e filtro per ruolo --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="cerca" value="Cerca per nome o email" />
                    <x-text-input id="cerca" name="cerca" type="text" class="mt-1 block w-full" value="{{ request('cerca') }}" />
                </div>
                <div class="min-w-[160px]">
                    <x-input-label for="role" value="Ruolo" />
                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tutti</option>
                        <option value="{{ \App\Models\User::ROLE_USER }}" @selected(request('role') === \App\Models\User::ROLE_USER)>Cliente</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected(request('role') === \App\Models\User::ROLE_ADMIN)>Admin</option>
                    </select>
                </div>
                <x-primary-button>{{ __('Filtra') }}</x-primary-button>
                @if (request('cerca') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 underline mb-2">Azzera filtri</a>
                @endif
            </form>

            <div class="bg-white rounded-2xl divide-y">
                @forelse ($utenti as $utente)
                    <a href="{{ route('admin.users.show', $utente) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <p class="font-medium text-gray-900">{{ $utente->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $utente->email }} &middot; {{ $utente->orders_count }} ordini &middot; {{ $utente->reviews_count }} recensioni
                            </p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full {{ $utente->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                            {{ $utente->isAdmin() ? 'Admin' : 'Cliente' }}
                        </span>
                    </a>
                @empty
                    <p class="p-4 text-gray-600">Nessun utente trovato.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $utenti->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
