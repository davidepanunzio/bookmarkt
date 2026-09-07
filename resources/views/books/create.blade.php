<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuovo libro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form method="POST" action="{{ route('admin.books.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @include('books._form')

                    <x-primary-button>{{ __('Crea libro') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
