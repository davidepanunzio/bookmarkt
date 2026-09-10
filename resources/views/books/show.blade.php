<x-app-layout>
    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <nav class="text-sm text-gray-500">
                <a href="{{ route('books.index') }}" class="hover:underline">Catalogo</a>
                <span class="mx-1">/</span>
                <a href="{{ route('books.index', ['categoria' => $book->category->slug]) }}" class="hover:underline">{{ $book->category->name }}</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $book->title }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr_280px] gap-10">
                {{-- Copertina --}}
                <div>
                    <x-book-cover :book="$book" class="w-full max-w-[240px] mx-auto lg:max-w-none" />
                </div>

                {{-- Contenuto principale --}}
                <div>
                    <span class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                        {{ $book->category->name }}
                    </span>
                    <h1 class="font-serif text-3xl font-bold text-gray-900 mt-1">{{ $book->title }}</h1>
                    <p class="text-gray-500 mt-1">{{ $book->author->name }}</p>
                    <x-star-rating :rating="$book->reviews_avg_rating" :count="$book->reviews_count" class="mt-2" />

                    <p class="mt-6 text-gray-700 whitespace-pre-line leading-relaxed">{{ $book->description }}</p>

                    {{-- Dettagli --}}
                    <div class="mt-8 border-t border-gray-200 divide-y divide-gray-200 text-sm">
                        <div class="flex py-2.5">
                            <span class="w-32 text-gray-500">Categoria</span>
                            <span class="text-gray-800">{{ $book->category->name }}</span>
                        </div>
                        <div class="flex py-2.5">
                            <span class="w-32 text-gray-500">Autore</span>
                            <span class="text-gray-800">{{ $book->author->name }}</span>
                        </div>
                        <div class="flex py-2.5">
                            <span class="w-32 text-gray-500">Pubblicato</span>
                            <span class="text-gray-800">{{ $book->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    @auth
                        @if (Auth::user()->isAdmin())
                            <div class="mt-6 flex items-center gap-4 text-sm">
                                <a href="{{ route('admin.books.edit', $book) }}" class="text-gray-500 underline">Modifica</a>
                                <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                                      onsubmit="return confirm('Eliminare questo libro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline">Elimina</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                {{-- Riquadro acquisto --}}
                <div class="bg-white rounded-2xl p-6 h-fit">
                    <p class="font-serif text-2xl font-bold text-gray-900">{{ number_format($book->price, 2, ',', '.') }} &euro;</p>
                    <p class="text-sm mt-1 {{ $book->isDisponibile() ? 'text-green-700' : 'text-red-700' }}">
                        {{ $book->isDisponibile() ? $book->stock.' disponibili' : 'Esaurito' }}
                    </p>

                    @auth
                        @if ($book->isDisponibile())
                            <form method="POST" action="{{ route('cart.store', $book) }}" class="mt-4 flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" max="{{ $book->stock }}"
                                       class="w-16 rounded-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <x-primary-button class="flex-1 justify-center">{{ __('Aggiungi al carrello') }}</x-primary-button>
                            </form>
                        @endif

                        <form method="POST" action="{{ $nelPreferiti ? route('wishlist.destroy', $book) : route('wishlist.store', $book) }}" class="mt-3">
                            @csrf
                            @if ($nelPreferiti) @method('DELETE') @endif
                            <button type="submit" class="w-full text-center text-sm underline {{ $nelPreferiti ? 'text-red-600' : 'text-gray-500' }}">
                                &hearts; {{ $nelPreferiti ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block mt-4 text-center text-indigo-600 underline">Accedi per acquistare</a>
                    @endauth
                </div>
            </div>

            {{-- Recensioni --}}
            <div class="bg-white rounded-2xl p-6" data-reveal>
                <h2 class="font-serif text-xl font-bold text-gray-900 mb-4">
                    Recensioni
                    <x-star-rating :rating="$book->reviews_avg_rating" :count="$book->reviews_count" class="inline-flex ms-2" />
                </h2>

                @auth
                    {{-- Form per scrivere/aggiornare la propria recensione --}}
                    <form method="POST" action="{{ route('reviews.store', $book) }}" class="mb-6 border-b border-gray-200 pb-6">
                        @csrf
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            {{ $miaRecensione ? 'Modifica la tua recensione' : 'Scrivi una recensione' }}
                        </p>
                        <div class="flex flex-wrap items-start gap-4">
                            <select name="rating" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected(old('rating', $miaRecensione->rating ?? 5) == $i)>
                                        {{ $i }} {{ $i === 1 ? 'stella' : 'stelle' }}
                                    </option>
                                @endfor
                            </select>
                            <div class="flex-1 min-w-[200px]">
                                <textarea name="comment" rows="2" placeholder="Cosa ne pensi di questo libro? (facoltativo)"
                                          class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('comment', $miaRecensione->comment ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                            </div>
                            <x-primary-button>{{ __('Salva') }}</x-primary-button>
                        </div>
                    </form>
                @endauth

                {{-- Elenco recensioni --}}
                @forelse ($book->reviews as $recensione)
                    <div class="py-3 {{ ! $loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">{{ $recensione->user->name }}</span>
                                <x-star-rating :rating="$recensione->rating" :count="null" class="inline-flex ms-2" />
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400">{{ $recensione->created_at->format('d/m/Y') }}</span>
                                @auth
                                    @if ($recensione->user_id === auth()->id() || Auth::user()->isAdmin())
                                        <form method="POST" action="{{ route('reviews.destroy', $recensione) }}"
                                              onsubmit="return confirm('Eliminare questa recensione?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 underline">Elimina</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        @if ($recensione->comment)
                            <p class="text-sm text-gray-700 mt-1">{{ $recensione->comment }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Nessuna recensione per questo libro. Scrivi la prima!</p>
                @endforelse
            </div>

            {{-- Se ti è piaciuto questo --}}
            @if ($libriSimili->isNotEmpty())
                <div data-reveal>
                    <h2 class="font-serif text-xl font-bold text-gray-900 mb-6">Se ti è piaciuto questo</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                        @foreach ($libriSimili as $libro)
                            @include('books._card')
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
