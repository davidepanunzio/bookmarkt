@php
    // Calcolati qui per evitare di doverli passare da ogni controller
    $carrelloCount = auth()->check() ? (auth()->user()->cart?->items()->count() ?? 0) : 0;
    $preferitiCount = auth()->check() ? auth()->user()->wishlist()->count() : 0;
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-30 bg-gray-100 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20 gap-4">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="shrink-0">
                <x-application-logo class="text-2xl" />
            </a>

            <!-- Catalogo e Contattaci (desktop) -->
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ route('books.index') }}"
                   class="text-sm font-medium {{ request()->routeIs('books.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                    {{ __('Catalogo') }}
                </a>
                @auth
                    @unless (Auth::user()->isAdmin())
                        <a href="{{ route('reports.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-indigo-600' : 'text-gray-700 hover:text-indigo-600' }}">
                            {{ __('Contattaci') }}
                        </a>
                    @endunless
                @endauth
            </div>

            <!-- Ricerca, icone e account -->
            <div class="flex items-center gap-2 ms-auto">
                <form method="GET" action="{{ route('books.index') }}" class="hidden lg:block">
                    <input type="text" name="cerca" value="{{ request('cerca') }}" placeholder="Cerca uno scaffale..."
                           class="w-40 lg:w-56 rounded-full border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </form>

                @auth
                    {{-- Preferiti --}}
                    <a href="{{ route('wishlist.index') }}"
                       class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ request()->routeIs('wishlist.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-200' }}"
                       title="Preferiti">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                        <span class="sr-only">Preferiti</span>
                    </a>

                    {{-- Carrello --}}
                    <a href="{{ route('cart.index') }}"
                       class="relative inline-flex items-center justify-center w-10 h-10 rounded-full {{ request()->routeIs('cart.*') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-200' }}"
                       title="Carrello">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 1.943-4.594 2.256-6.75L8.25 6h-4.5m3.87 5.25L6.75 6M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm11.25 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span class="sr-only">Carrello</span>
                        @if ($carrelloCount > 0)
                            <span class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-indigo-600 rounded-full">
                                {{ $carrelloCount }}
                            </span>
                        @endif
                    </a>
                @endauth

                <div class="hidden lg:block">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none ps-1">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-1 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if (Auth::user()->isAdmin())
                                    <x-dropdown-link :href="route('admin.dashboard')">
                                        {{ __('Pannello Admin') }}
                                    </x-dropdown-link>
                                @endif
                                <x-dropdown-link :href="route('orders.index')">
                                    {{ __('I miei ordini') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profilo') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Esci') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">{{ __('Accedi') }}</a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-indigo-700">
                                {{ __('Registrati') }}
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Hamburger (mobile/tablet): visibile fino a lg, dove compare il menu desktop -->
                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center w-10 h-10 rounded-full text-gray-500 hover:text-gray-700 hover:bg-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-gray-200">
        <div class="px-4 py-4 space-y-4">
            <form method="GET" action="{{ route('books.index') }}">
                <input type="text" name="cerca" value="{{ request('cerca') }}" placeholder="Cerca uno scaffale..."
                       class="w-full rounded-full border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </form>

            <a href="{{ route('books.index') }}"
               class="block text-sm font-medium {{ request()->routeIs('books.*') ? 'text-indigo-600' : 'text-gray-700' }}">
                {{ __('Catalogo') }}
            </a>
            @auth
                @unless (Auth::user()->isAdmin())
                    <a href="{{ route('reports.index') }}"
                       class="block text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-indigo-600' : 'text-gray-700' }}">
                        {{ __('Contattaci') }}
                    </a>
                @endunless
            @endauth
            <div class="pt-3 border-t border-gray-200 space-y-2">
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-sm font-medium text-gray-800">Pannello Admin</a>
                    @endif
                    <a href="{{ route('orders.index') }}" class="block text-sm font-medium text-gray-800">I miei ordini</a>
                    <a href="{{ route('profile.edit') }}" class="block text-sm font-medium text-gray-800">Profilo</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block text-sm font-medium text-gray-800">Esci</a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-800">Accedi</a>
                    <a href="{{ route('register') }}" class="block text-sm font-medium text-gray-800">Registrati</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
