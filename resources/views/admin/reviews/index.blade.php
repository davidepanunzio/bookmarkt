<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione recensioni') }}
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
                <span class="font-semibold text-gray-800">Recensioni</span>
            </nav>

            {{-- Filtro per titolo del libro e per voto --}}
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="bg-white p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <x-input-label for="cerca" value="Cerca per titolo libro" />
                    <x-text-input id="cerca" name="cerca" type="text" class="mt-1 block w-full" value="{{ request('cerca') }}" />
                </div>
                <div class="min-w-[150px]">
                    <x-input-label for="rating" value="Voto" />
                    <select id="rating" name="rating" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Tutti</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} stelle</option>
                        @endfor
                    </select>
                </div>
                <x-primary-button>{{ __('Filtra') }}</x-primary-button>
                @if (request('cerca') || request('rating'))
                    <a href="{{ route('admin.reviews.index') }}" class="text-sm text-gray-500 underline mb-2">Azzera filtri</a>
                @endif
            </form>

            <div class="bg-white rounded-lg shadow-sm divide-y">
                @forelse ($recensioni as $recensione)
                    <div class="p-4 flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <a href="{{ route('books.show', $recensione->book) }}" class="font-medium text-gray-900 hover:underline">
                                {{ $recensione->book->title }}
                            </a>
                            <div class="flex items-center gap-2 mt-1">
                                <x-star-rating :rating="$recensione->rating" :count="null" />
                                <span class="text-sm text-gray-500">di {{ $recensione->user->name }}</span>
                                <span class="text-xs text-gray-400">&middot; {{ $recensione->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if ($recensione->comment)
                                <p class="text-sm text-gray-700 mt-1">{{ $recensione->comment }}</p>
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
                    <p class="p-4 text-gray-600">Nessuna recensione trovata.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $recensioni->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
