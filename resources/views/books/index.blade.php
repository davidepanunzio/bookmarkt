<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catalogo libri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Barra di ricerca e filtri --}}
            <form method="GET" action="{{ route('books.index') }}" class="bg-white p-4 rounded-lg shadow-sm space-y-4">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <x-input-label for="cerca" value="Cerca per titolo o autore" />
                        <x-text-input id="cerca" name="cerca" type="text" class="mt-1 block w-full" value="{{ request('cerca') }}" placeholder="Es. Il nome della rosa, Eco..." />
                    </div>

                    <div class="min-w-[180px]">
                        <x-input-label for="categoria" value="Categoria" />
                        <select id="categoria" name="categoria" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Tutte</option>
                            @foreach ($categorie as $categoria)
                                <option value="{{ $categoria->slug }}" @selected(request('categoria') === $categoria->slug)>
                                    {{ $categoria->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[180px]">
                        <x-input-label for="ordina" value="Ordina per" />
                        <select id="ordina" name="ordina" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="recenti" @selected(request('ordina', 'recenti') === 'recenti')>Più recenti</option>
                            <option value="prezzo_asc" @selected(request('ordina') === 'prezzo_asc')>Prezzo crescente</option>
                            <option value="prezzo_desc" @selected(request('ordina') === 'prezzo_desc')>Prezzo decrescente</option>
                            <option value="titolo" @selected(request('ordina') === 'titolo')>Titolo (A-Z)</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 items-end">
                    <div class="w-28">
                        <x-input-label for="prezzo_min" value="Prezzo min" />
                        <x-text-input id="prezzo_min" name="prezzo_min" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ request('prezzo_min') }}" placeholder="0" />
                    </div>

                    <div class="w-28">
                        <x-input-label for="prezzo_max" value="Prezzo max" />
                        <x-text-input id="prezzo_max" name="prezzo_max" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ request('prezzo_max') }}" placeholder="50" />
                    </div>

                    <label class="flex items-center gap-2 text-sm text-gray-700 mb-2">
                        <input type="checkbox" name="disponibili" value="1" @checked(request('disponibili'))
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Solo disponibili
                    </label>

                    <x-primary-button>{{ __('Filtra') }}</x-primary-button>

                    @if (request()->anyFilled(['cerca', 'categoria', 'prezzo_min', 'prezzo_max', 'disponibili']) || request('ordina', 'recenti') !== 'recenti')
                        <a href="{{ route('books.index') }}" class="text-sm text-gray-500 underline mb-2">Azzera filtri</a>
                    @endif

                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.books.create') }}" class="ms-auto text-sm text-indigo-600 underline mb-2">
                                + Aggiungi libro
                            </a>
                        @endif
                    @endauth
                </div>
            </form>

            {{-- Griglia dei libri --}}
            @if ($libri->isEmpty())
                <p class="text-gray-600">Nessun libro trovato.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($libri as $libro)
                        <div class="relative bg-white rounded-lg shadow-sm hover:shadow-md transition p-5 flex flex-col">
                            @auth
                                @php $nePreferiti = in_array($libro->id, $preferitiIds); @endphp
                                <form method="POST"
                                      action="{{ $nePreferiti ? route('wishlist.destroy', $libro) : route('wishlist.store', $libro) }}"
                                      class="absolute top-3 right-3 z-10">
                                    @csrf
                                    @if ($nePreferiti) @method('DELETE') @endif
                                    <button type="submit" class="text-xl {{ $nePreferiti ? 'text-red-500 hover:text-red-700' : 'text-gray-300 hover:text-red-400' }}"
                                            title="{{ $nePreferiti ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}">
                                        &hearts;
                                    </button>
                                </form>
                            @endauth

                            <a href="{{ route('books.show', $libro) }}" class="flex flex-col flex-1">
                                <img src="{{ $libro->coverUrl() }}" alt="Copertina di {{ $libro->title }}"
                                     class="w-full h-48 object-cover rounded-md mb-3 bg-gray-100">
                                <span class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                                    {{ $libro->category->name }}
                                </span>
                                <h3 class="mt-1 text-lg font-semibold text-gray-900">{{ $libro->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $libro->author->name }}</p>
                                <x-star-rating :rating="$libro->reviews_avg_rating" :count="$libro->reviews_count" class="mt-1" />
                                <div class="mt-auto pt-4 flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($libro->price, 2, ',', '.') }} &euro;</span>
                                    @if ($libro->isDisponibile())
                                        <span class="text-xs text-green-700 bg-green-100 px-2 py-1 rounded-full">Disponibile</span>
                                    @else
                                        <span class="text-xs text-red-700 bg-red-100 px-2 py-1 rounded-full">Esaurito</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $libri->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
