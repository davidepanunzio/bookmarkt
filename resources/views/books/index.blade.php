<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-10" x-data="{ filtriAperti: false }">
                {{-- Pulsante per aprire/chiudere i filtri, solo su mobile e tablet --}}
                <button type="button" @click="filtriAperti = ! filtriAperti"
                        class="lg:hidden flex items-center justify-between gap-2 bg-white border border-gray-300 rounded-full px-4 py-2.5 text-sm font-medium text-gray-700">
                    <span class="inline-flex items-center gap-2">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M6 9h12M10 13.5h4" />
                        </svg>
                        {{ __('Filtri') }}
                    </span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': filtriAperti }">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                {{-- Sidebar filtri: sempre visibile da lg in su, aperta/chiusa dal pulsante sotto lg --}}
                <aside x-cloak :class="filtriAperti ? 'block' : 'hidden'" class="lg:block lg:w-64 shrink-0">
                    <form method="GET" action="{{ route('books.index') }}" class="space-y-6">
                        <div>
                            <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Filtro</h2>

                            {{-- Filtri attivi, rimovibili --}}
                            @if (request()->anyFilled(['cerca', 'categoria', 'prezzo_min', 'prezzo_max', 'disponibili']))
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if (request('categoria'))
                                        @php $cat = $categorie->firstWhere('slug', request('categoria')); @endphp
                                        <a href="{{ route('books.index', request()->except('categoria')) }}" class="text-xs bg-white border border-gray-300 rounded-full px-3 py-1 text-gray-700">
                                            {{ $cat->name ?? request('categoria') }} &times;
                                        </a>
                                    @endif
                                    @if (request('disponibili'))
                                        <a href="{{ route('books.index', request()->except('disponibili')) }}" class="text-xs bg-white border border-gray-300 rounded-full px-3 py-1 text-gray-700">
                                            In stock &times;
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <x-text-input name="cerca" type="text" class="block w-full rounded-full text-sm" value="{{ request('cerca') }}" placeholder="Cerca titolo o autore..." />
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Categoria</h3>
                            <div class="space-y-1.5">
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="categoria" value="" @checked(! request('categoria')) class="text-indigo-600 focus:ring-indigo-500">
                                    Tutte
                                </label>
                                @foreach ($categorie as $categoria)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input type="radio" name="categoria" value="{{ $categoria->slug }}" @checked(request('categoria') === $categoria->slug) class="text-indigo-600 focus:ring-indigo-500">
                                        {{ $categoria->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Prezzo</h3>
                            <div class="flex items-center gap-2">
                                <x-text-input name="prezzo_min" type="number" step="0.01" min="0" class="w-full rounded-full text-sm" value="{{ request('prezzo_min') }}" placeholder="0" />
                                <span class="text-gray-400">-</span>
                                <x-text-input name="prezzo_max" type="number" step="0.01" min="0" class="w-full rounded-full text-sm" value="{{ request('prezzo_max') }}" placeholder="50" />
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Ordina per</h3>
                            <select name="ordina" class="block w-full rounded-full border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="recenti" @selected(request('ordina', 'recenti') === 'recenti')>Più recenti</option>
                                <option value="prezzo_asc" @selected(request('ordina') === 'prezzo_asc')>Prezzo crescente</option>
                                <option value="prezzo_desc" @selected(request('ordina') === 'prezzo_desc')>Prezzo decrescente</option>
                                <option value="titolo" @selected(request('ordina') === 'titolo')>Titolo (A-Z)</option>
                            </select>
                        </div>

                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="disponibili" value="1" @checked(request('disponibili'))
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Solo disponibili
                        </label>

                        <div class="flex items-center gap-3">
                            <x-primary-button class="w-full justify-center">{{ __('Filtra') }}</x-primary-button>
                        </div>
                        @if (request()->anyFilled(['cerca', 'categoria', 'prezzo_min', 'prezzo_max', 'disponibili']) || request('ordina', 'recenti') !== 'recenti')
                            <a href="{{ route('books.index') }}" class="block text-center text-sm text-gray-500 underline">Azzera filtri</a>
                        @endif
                    </form>

                    @auth
                        @if (Auth::user()->isAdmin())
                            <div class="mt-8 bg-green-100 border border-green-200 rounded-2xl p-4">
                                <p class="text-sm font-semibold text-green-800">Area amministratore</p>
                                <p class="text-sm text-green-700 mt-1">Aggiungi un nuovo titolo al catalogo.</p>
                                <a href="{{ route('admin.books.create') }}" class="inline-block mt-2 text-sm font-semibold text-green-800 underline">+ Aggiungi libro</a>
                            </div>
                        @endif
                    @endauth
                </aside>

                {{-- Risultati --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline justify-between flex-wrap gap-2 mb-6">
                        <h1 class="font-serif text-3xl font-bold text-gray-900">
                            {{ request('categoria') ? ($categorie->firstWhere('slug', request('categoria'))->name ?? 'Catalogo') : 'Catalogo' }}
                        </h1>
                        <p class="text-sm text-gray-500">{{ $libri->total() }} {{ $libri->total() === 1 ? 'titolo' : 'titoli' }}</p>
                    </div>

                    @if ($libri->isEmpty())
                        <p class="text-gray-600">Nessun libro trovato.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-10">
                            @foreach ($libri as $libro)
                                @include('books._card')
                            @endforeach
                        </div>

                        <div class="mt-10">
                            {{ $libri->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
