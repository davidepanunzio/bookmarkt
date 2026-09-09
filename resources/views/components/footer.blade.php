@php
    // Calcolato qui per evitare di dover passare i dati da ogni controller
    $categorieFooter = \App\Models\Category::orderBy('name')->get();
@endphp

<footer class="border-t border-gray-200 bg-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-8">
            {{-- Marchio + info pagamento --}}
            <div class="col-span-2 sm:col-span-1">
                <x-application-logo class="text-lg" />
                <p class="mt-3 text-sm text-gray-500">
                    Il tuo negozio di libri online: catalogo curato, recensioni vere, ordini semplici.
                </p>
                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-gray-400">Pagamenti accettati</p>
                <p class="text-sm text-gray-600">Contrassegno &middot; Bonifico bancario</p>
            </div>

            {{-- Il negozio --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Il negozio</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500">
                    <li><a href="{{ route('books.index') }}" class="hover:text-indigo-600">Tutti i libri</a></li>
                    @foreach ($categorieFooter as $categoria)
                        <li>
                            <a href="{{ route('books.index', ['categoria' => $categoria->slug]) }}" class="hover:text-indigo-600">
                                {{ $categoria->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Account --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Il tuo account</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500">
                    @auth
                        <li><a href="{{ route('profile.edit') }}" class="hover:text-indigo-600">Il mio profilo</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-indigo-600">I miei ordini</a></li>
                        <li><a href="{{ route('wishlist.index') }}" class="hover:text-indigo-600">Preferiti</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-indigo-600">Carrello</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600">Accedi</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-indigo-600">Registrati</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Assistenza --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Assistenza</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-500">
                    @if (auth()->check() && ! Auth::user()->isAdmin())
                        <li><a href="{{ route('reports.create') }}" class="hover:text-indigo-600">Contattaci</a></li>
                        <li><a href="{{ route('reports.index') }}" class="hover:text-indigo-600">Le mie richieste</a></li>
                    @elseif (! auth()->check())
                        <li><a href="{{ route('login') }}" class="hover:text-indigo-600">Accedi per contattarci</a></li>
                    @endif
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-indigo-600">Informativa sulla privacy</a></li>
                    <li><a href="{{ route('legal.termini') }}" class="hover:text-indigo-600">Termini e condizioni</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} BookMarkt &mdash; progetto d'esame ITS Web Developer.
            </p>
            <div class="flex gap-4 text-xs text-gray-400">
                <a href="{{ route('legal.privacy') }}" class="hover:text-gray-600">Informativa sulla privacy</a>
                <a href="{{ route('legal.termini') }}" class="hover:text-gray-600">Termini e condizioni</a>
            </div>
        </div>
    </div>
</footer>
