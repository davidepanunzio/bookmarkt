<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Modifica libro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.books.update', $book) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('books._form')

                    <x-primary-button>{{ __('Salva modifiche') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
