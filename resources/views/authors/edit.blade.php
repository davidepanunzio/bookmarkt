<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Modifica autore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('authors._form')

                    <x-primary-button>{{ __('Salva modifiche') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
