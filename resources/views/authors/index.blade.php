<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestione autori') }}
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
                    <a href="{{ route('admin.categories.index') }}" class="underline">Categorie</a>
                    <span class="font-semibold text-gray-800">Autori</span>
                </nav>
                <a href="{{ route('admin.authors.create') }}">
                    <x-primary-button>{{ __('+ Nuovo autore') }}</x-primary-button>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm divide-y">
                @forelse ($autori as $autore)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $autore->name }}</p>
                            <p class="text-sm text-gray-500">{{ $autore->books_count }} libri</p>
                        </div>
                        <div class="space-x-3 text-sm">
                            <a href="{{ route('admin.authors.edit', $autore) }}" class="text-indigo-600 underline">Modifica</a>
                            <form method="POST" action="{{ route('admin.authors.destroy', $autore) }}" class="inline"
                                  onsubmit="return confirm('Eliminare questo autore? Verranno eliminati anche i suoi libri.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 underline">Elimina</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-gray-600">Nessun autore presente.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
