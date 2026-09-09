<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900">
            {{ __('Il mio profilo') }}
        </h2>
    </x-slot>

    <div class="pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('status') === 'profile-updated')
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    Profilo aggiornato con successo.
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-8 items-start">
                {{-- Sidebar account --}}
                <aside class="bg-white rounded-2xl p-6 lg:sticky lg:top-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-serif font-bold text-2xl">
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                        </div>
                        <p class="mt-3 font-serif font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">Membro dal {{ $user->created_at->format('m/Y') }}</p>
                        @if ($user->isAdmin())
                            <span class="mt-2 text-xs font-medium px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">Admin</span>
                        @endif
                    </div>

                    <nav class="mt-6 space-y-1 text-sm">
                        <a href="#ordini" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Ordini</a>
                        <a href="#preferiti" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Preferiti</a>
                        <a href="#impostazioni" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Impostazioni account</a>
                        @if ($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Pannello Admin</a>
                        @endif
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-100">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50">
                            Esci
                        </button>
                    </form>
                </aside>

                <div class="space-y-10">
                    {{-- Statistiche --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white rounded-2xl p-5">
                            <p class="text-sm text-gray-500">Ordini effettuati</p>
                            <p class="font-serif text-2xl font-bold text-gray-900">{{ $numeroOrdini }}</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5">
                            <p class="text-sm text-gray-500">Totale speso</p>
                            <p class="font-serif text-2xl font-bold text-gray-900">{{ number_format($totaleSpeso, 2, ',', '.') }} &euro;</p>
                        </div>
                        <div class="bg-white rounded-2xl p-5">
                            <p class="text-sm text-gray-500">Libri preferiti</p>
                            <p class="font-serif text-2xl font-bold text-gray-900">{{ $numeroPreferiti }}</p>
                        </div>
                    </div>

                    {{-- Ordini recenti --}}
                    <section id="ordini" class="scroll-mt-6" data-reveal>
                        <div class="flex items-baseline justify-between mb-4">
                            <h3 class="font-serif text-xl font-bold text-gray-900">Ordini recenti</h3>
                            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 underline">Vedi tutti</a>
                        </div>

                        @if ($ultimiOrdini->isEmpty())
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-600 text-sm">
                                Non hai ancora effettuato ordini.
                            </div>
                        @else
                            <div class="bg-white rounded-2xl divide-y divide-gray-100">
                                @foreach ($ultimiOrdini as $ordine)
                                    <a href="{{ route('orders.show', $ordine) }}" class="p-4 flex items-center justify-between hover:bg-gray-50">
                                        <div>
                                            <p class="font-medium text-gray-900">Ordine #{{ $ordine->id }}</p>
                                            <p class="text-sm text-gray-500">{{ $ordine->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <x-order-status-badge :status="$ordine->status" />
                                            <span class="font-semibold text-gray-900 w-20 text-right">{{ number_format($ordine->total, 2, ',', '.') }} &euro;</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    {{-- Preferiti recenti --}}
                    <section id="preferiti" class="scroll-mt-6" data-reveal>
                        <div class="flex items-baseline justify-between mb-4">
                            <h3 class="font-serif text-xl font-bold text-gray-900">Preferiti</h3>
                            <a href="{{ route('wishlist.index') }}" class="text-sm text-indigo-600 underline">Vedi tutti</a>
                        </div>

                        @if ($preferitiRecenti->isEmpty())
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-600 text-sm">
                                Non hai ancora salvato libri tra i preferiti.
                                <a href="{{ route('books.index') }}" class="text-indigo-600 underline">Vai al catalogo</a>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach ($preferitiRecenti as $libro)
                                    <a href="{{ route('books.show', $libro) }}">
                                        <x-book-cover :book="$libro" class="w-full" />
                                        <p class="mt-2 text-sm font-medium text-gray-900 truncate">{{ $libro->title }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($libro->price, 2, ',', '.') }} &euro;</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    {{-- Impostazioni account --}}
                    <section id="impostazioni" class="scroll-mt-6 space-y-6">
                        <h3 class="font-serif text-xl font-bold text-gray-900">Impostazioni account</h3>

                        <div class="p-4 sm:p-8 bg-white rounded-2xl">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white rounded-2xl">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white rounded-2xl">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
