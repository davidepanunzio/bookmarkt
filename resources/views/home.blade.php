<x-app-layout>
    <div class="py-10 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Carosello con gli ultimi arrivi --}}
            @if ($libriHero->isNotEmpty())
                <section
                    x-data="{
                        attiva: 0,
                        totale: {{ $libriHero->count() }},
                        timer: null,
                        avvia() { this.timer = setInterval(() => { this.attiva = (this.attiva + 1) % this.totale }, 6000) },
                        ferma() { clearInterval(this.timer) },
                        vai(i) { this.attiva = i; this.ferma(); this.avvia() },
                    }"
                    x-init="avvia()"
                    @mouseenter="ferma()"
                    @mouseleave="avvia()"
                    class="relative"
                >
                    @foreach ($libriHero as $i => $libro)
                        <div x-show="attiva === {{ $i }}" x-cloak
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                            @include('home._hero-slide', ['libro' => $libro])
                        </div>
                    @endforeach

                    @if ($libriHero->count() > 1)
                        {{-- Frecce e indicatori, in riga sotto la slide (mai sovrapposti al contenuto) --}}
                        <div class="flex items-center justify-center gap-4 mt-8">
                            <button @click="vai((attiva - 1 + totale) % totale)"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow-md text-gray-600 hover:text-indigo-600 shrink-0"
                                    aria-label="Slide precedente">
                                &lsaquo;
                            </button>

                            <div class="flex items-center gap-2">
                                @foreach ($libriHero as $i => $libro)
                                    <button @click="vai({{ $i }})"
                                            :class="attiva === {{ $i }} ? 'w-6 bg-indigo-600' : 'w-2 bg-gray-300'"
                                            class="h-2 rounded-full transition-all duration-300"
                                            aria-label="Vai alla slide {{ $i + 1 }}">
                                    </button>
                                @endforeach
                            </div>

                            <button @click="vai((attiva + 1) % totale)"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white shadow-md text-gray-600 hover:text-indigo-600 shrink-0"
                                    aria-label="Slide successiva">
                                &rsaquo;
                            </button>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Sfoglia per categoria --}}
            @if ($categorie->isNotEmpty())
                <section data-reveal>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @foreach ($categorie as $categoria)
                            <a href="{{ route('books.index', ['categoria' => $categoria->slug]) }}"
                               class="inline-flex items-center rounded-full border border-gray-300 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition-colors duration-200 hover:border-indigo-600 hover:text-indigo-600">
                                {{ $categoria->name }}
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Ultime uscite --}}
            @if ($ultimiLibri->isNotEmpty())
                <section data-reveal>
                    <div class="flex items-baseline justify-between mb-8">
                        <h2 class="font-serif text-2xl font-bold text-gray-900">Ultime uscite</h2>
                        <a href="{{ route('books.index') }}" class="text-sm text-indigo-600 underline">Vedi tutto il catalogo</a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
                        @foreach ($ultimiLibri as $libro)
                            @include('books._card')
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Best seller --}}
            @if ($bestSeller->isNotEmpty())
                <section data-reveal>
                    <h2 class="font-serif text-2xl font-bold text-gray-900 mb-8">Best seller</h2>
                    <div class="bg-white rounded-2xl divide-y divide-gray-100 shadow-sm">
                        @foreach ($bestSeller as $posizione => $voce)
                            @php $nePreferitiBestSeller = in_array($voce->book->id, $preferitiIds); @endphp
                            <div class="flex items-center gap-4 p-4 sm:p-5 hover:bg-gray-50 transition-colors duration-200">
                                <span class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white text-sm font-bold">
                                    {{ $posizione + 1 }}
                                </span>

                                <a href="{{ route('books.show', $voce->book) }}" class="flex items-center gap-4 min-w-0 flex-1">
                                    <x-book-cover :book="$voce->book" class="w-12 shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-serif font-semibold text-gray-900 truncate">{{ $voce->book->title }}</h3>
                                        <p class="text-sm text-gray-500 truncate">{{ $voce->book->author->name }}</p>
                                    </div>
                                </a>

                                <div class="hidden sm:block shrink-0 text-right">
                                    <p class="font-bold text-gray-900">{{ number_format($voce->book->price, 2, ',', '.') }} &euro;</p>
                                    <p class="text-xs text-gray-400">{{ $voce->venduti }} venduti</p>
                                </div>

                                <div class="flex items-center gap-1 shrink-0">
                                    @auth
                                        <form method="POST"
                                              action="{{ $nePreferitiBestSeller ? route('wishlist.destroy', $voce->book) : route('wishlist.store', $voce->book) }}">
                                            @csrf
                                            @if ($nePreferitiBestSeller) @method('DELETE') @endif
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 {{ $nePreferitiBestSeller ? 'text-red-500' : 'text-gray-500' }}"
                                                    title="{{ $nePreferitiBestSeller ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}">
                                                <svg viewBox="0 0 24 24" fill="{{ $nePreferitiBestSeller ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-100"
                                           title="Accedi per aggiungere ai preferiti">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </a>
                                    @endauth

                                    @if ($voce->book->isDisponibile())
                                        @auth
                                            <form method="POST" action="{{ route('cart.store', $voce->book) }}">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600"
                                                        title="Aggiungi al carrello">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 hover:bg-gray-100 hover:text-indigo-600"
                                               title="Accedi per aggiungere al carrello">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                </svg>
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
