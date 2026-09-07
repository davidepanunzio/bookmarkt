<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $book->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between flex-wrap gap-6">
                    <img src="{{ $book->coverUrl() }}" alt="Copertina di {{ $book->title }}"
                         class="w-40 h-56 object-cover rounded-md bg-gray-100 shrink-0">

                    <div class="flex-1 min-w-[200px]">
                        <span class="text-xs uppercase tracking-wide text-indigo-600 font-semibold">
                            {{ $book->category->name }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $book->title }}</h1>
                        <p class="text-gray-500">di {{ $book->author->name }}</p>
                        <x-star-rating :rating="$book->reviews_avg_rating" :count="$book->reviews_count" class="mt-1" />
                    </div>

                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($book->price, 2, ',', '.') }} &euro;</p>
                        @if ($book->isDisponibile())
                            <p class="text-sm text-green-700">{{ $book->stock }} disponibili</p>
                        @else
                            <p class="text-sm text-red-700">Esaurito</p>
                        @endif
                    </div>
                </div>

                <p class="mt-6 text-gray-700 whitespace-pre-line">{{ $book->description }}</p>

                <div class="mt-6 flex flex-wrap items-center gap-4">
                    @auth
                        @if ($book->isDisponibile())
                            <form method="POST" action="{{ route('cart.store', $book) }}" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="1" min="1" max="{{ $book->stock }}"
                                       class="w-20 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <x-primary-button>{{ __('Aggiungi al carrello') }}</x-primary-button>
                            </form>
                        @endif

                        <form method="POST" action="{{ $nelPreferiti ? route('wishlist.destroy', $book) : route('wishlist.store', $book) }}">
                            @csrf
                            @if ($nelPreferiti) @method('DELETE') @endif
                            <button type="submit" class="text-sm underline {{ $nelPreferiti ? 'text-red-600' : 'text-gray-500' }}">
                                &hearts; {{ $nelPreferiti ? 'Rimuovi dai preferiti' : 'Aggiungi ai preferiti' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-indigo-600 underline">Accedi per acquistare</a>
                    @endauth

                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.books.edit', $book) }}" class="text-sm text-gray-500 underline">Modifica</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                                  onsubmit="return confirm('Eliminare questo libro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 underline">Elimina</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Recensioni --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    Recensioni
                    <x-star-rating :rating="$book->reviews_avg_rating" :count="$book->reviews_count" class="inline-flex ms-2" />
                </h3>

                @auth
                    {{-- Form per scrivere/aggiornare la propria recensione --}}
                    <form method="POST" action="{{ route('reviews.store', $book) }}" class="mb-6 border-b pb-6">
                        @csrf
                        <p class="text-sm font-medium text-gray-700 mb-2">
                            {{ $miaRecensione ? 'Modifica la tua recensione' : 'Scrivi una recensione' }}
                        </p>
                        <div class="flex flex-wrap items-start gap-4">
                            <select name="rating" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected(old('rating', $miaRecensione->rating ?? 5) == $i)>
                                        {{ $i }} {{ $i === 1 ? 'stella' : 'stelle' }}
                                    </option>
                                @endfor
                            </select>
                            <div class="flex-1 min-w-[200px]">
                                <textarea name="comment" rows="2" placeholder="Cosa ne pensi di questo libro? (facoltativo)"
                                          class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('comment', $miaRecensione->comment ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                            </div>
                            <x-primary-button>{{ __('Salva') }}</x-primary-button>
                        </div>
                    </form>
                @endauth

                {{-- Elenco recensioni --}}
                @forelse ($book->reviews as $recensione)
                    <div class="py-3 {{ ! $loop->last ? 'border-b' : '' }}">
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

            <a href="{{ route('books.index') }}" class="text-sm text-gray-500 underline">&larr; Torna al catalogo</a>
        </div>
    </div>
</x-app-layout>
