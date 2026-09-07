{{-- Campi condivisi tra il form di creazione e quello di modifica del libro --}}

<div>
    <x-input-label for="title" value="Titolo" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                  value="{{ old('title', $book->title ?? '') }}" required autofocus />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div>
    <x-input-label for="description" value="Descrizione" />
    <textarea id="description" name="description" rows="4"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $book->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="price" value="Prezzo (&euro;)" />
        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full"
                      value="{{ old('price', $book->price ?? '') }}" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="stock" value="Quantità in magazzino" />
        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full"
                      value="{{ old('stock', $book->stock ?? 0) }}" required />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="category_id" value="Categoria" />
        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">-- Seleziona --</option>
            @foreach ($categorie as $categoria)
                <option value="{{ $categoria->id }}" @selected(old('category_id', $book->category_id ?? '') == $categoria->id)>
                    {{ $categoria->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="author_id" value="Autore" />
        <select id="author_id" name="author_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
            <option value="">-- Seleziona --</option>
            @foreach ($autori as $autore)
                <option value="{{ $autore->id }}" @selected(old('author_id', $book->author_id ?? '') == $autore->id)>
                    {{ $autore->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('author_id')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="cover_image" value="Copertina (opzionale, max 2 MB)" />

    @isset($book)
        @if ($book->cover_image)
            <img src="{{ $book->coverUrl() }}" alt="Copertina attuale" class="w-24 h-32 object-cover rounded-md my-2">
        @endif
    @endisset

    <input id="cover_image" name="cover_image" type="file" accept="image/*"
           class="mt-1 block w-full text-sm text-gray-600 file:me-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
</div>
