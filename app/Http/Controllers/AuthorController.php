<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    /**
     * Elenco degli autori (area amministrazione).
     */
    public function index(): View
    {
        $autori = Author::withCount('books')->orderBy('name')->get();

        return view('authors.index', compact('autori'));
    }

    /**
     * Form di creazione autore.
     */
    public function create(): View
    {
        return view('authors.create');
    }

    /**
     * Salva un nuovo autore.
     */
    public function store(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        Author::create($dati);

        return redirect()->route('admin.authors.index')->with('status', 'Autore creato con successo.');
    }

    /**
     * Form di modifica autore.
     */
    public function edit(Author $author): View
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Aggiorna un autore esistente.
     */
    public function update(Request $request, Author $author): RedirectResponse
    {
        $dati = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        $author->update($dati);

        return redirect()->route('admin.authors.index')->with('status', 'Autore aggiornato con successo.');
    }

    /**
     * Elimina un autore, a meno che contenga ancora dei libri
     * (altrimenti la cancellazione andrebbe a cascata sui libri collegati).
     */
    public function destroy(Author $author): RedirectResponse
    {
        if ($author->books()->exists()) {
            return redirect()->route('admin.authors.index')->with('status', 'Autore non eliminabile: ha ancora dei libri.');
        }

        $author->delete();

        return redirect()->route('admin.authors.index')->with('status', 'Autore eliminato.');
    }
}
