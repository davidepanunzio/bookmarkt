<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifica autore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
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
