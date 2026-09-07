<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Elenco delle categorie (area amministrazione).
     */
    public function index(): View
    {
        $categorie = Category::withCount('books')->orderBy('name')->get();

        return view('categories.index', compact('categorie'));
    }

    /**
     * Form di creazione categoria.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Salva una nuova categoria.
     */
    public function store(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $dati['slug'] = Str::slug($dati['name']);

        Category::create($dati);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria creata con successo.');
    }

    /**
     * Form di modifica categoria.
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Aggiorna una categoria esistente.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $dati = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        $dati['slug'] = Str::slug($dati['name']);

        $category->update($dati);

        return redirect()->route('admin.categories.index')->with('status', 'Categoria aggiornata con successo.');
    }

    /**
     * Elimina una categoria (e, a cascata, i libri collegati).
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categoria eliminata.');
    }
}
