{{-- Campi condivisi tra creazione e modifica autore --}}

<div>
    <x-input-label for="name" value="Nome e cognome" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  value="{{ old('name', $author->name ?? '') }}" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="bio" value="Biografia (opzionale)" />
    <textarea id="bio" name="bio" rows="4"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $author->bio ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
</div>
