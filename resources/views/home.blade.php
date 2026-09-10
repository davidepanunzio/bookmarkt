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
                                    <x-wishlist-button :book="$voce->book" :preferiti-ids="$preferitiIds" class="w-9 h-9 hover:bg-gray-100" />
                                    <x-cart-button :book="$voce->book" class="w-9 h-9 hover:bg-gray-100" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
