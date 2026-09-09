<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Gestione categorie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-between items-center">
                <nav class="text-sm text-gray-500 space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="underline">Dashboard</a>
                    <span class="font-semibold text-gray-800">Categorie</span>
                    <a href="{{ route('admin.authors.index') }}" class="underline">Autori</a>
                </nav>
                <a href="{{ route('admin.categories.create') }}">
                    <x-primary-button>{{ __('+ Nuova categoria') }}</x-primary-button>
                </a>
            </div>

            <div class="bg-white rounded-2xl divide-y">
                @forelse ($categorie as $categoria)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $categoria->name }}</p>
                            <p class="text-sm text-gray-500">{{ $categoria->books_count }} libri</p>
                        </div>
                        <div class="space-x-3 text-sm">
                            <a href="{{ route('admin.categories.edit', $categoria) }}" class="text-indigo-600 underline">Modifica</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $categoria) }}" class="inline"
                                  onsubmit="return confirm('Eliminare questa categoria? Verranno eliminati anche i suoi libri.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 underline">Elimina</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-gray-600">Nessuna categoria presente.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
