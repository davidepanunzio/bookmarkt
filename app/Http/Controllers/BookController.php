<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Catalogo pubblico dei libri: ricerca (titolo o autore), filtro per categoria,
     * fascia di prezzo, disponibilità e ordinamento.
     */
    public function index(Request $request): View
    {
        $libri = Book::query()
            ->with(['category', 'author'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->when($request->filled('cerca'), function ($query) use ($request) {
                $testo = $request->input('cerca');
                $query->where(function ($q) use ($testo) {
                    $q->where('title', 'like', "%{$testo}%")
                        ->orWhereHas('author', fn ($a) => $a->where('name', 'like', "%{$testo}%"));
                });
            })
            ->when($request->filled('categoria'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->input('categoria')));
            })
            ->when($request->filled('prezzo_min'), function ($query) use ($request) {
                $query->where('price', '>=', $request->input('prezzo_min'));
            })
            ->when($request->filled('prezzo_max'), function ($query) use ($request) {
                $query->where('price', '<=', $request->input('prezzo_max'));
            })
            ->when($request->boolean('disponibili'), function ($query) {
                $query->where('stock', '>', 0);
            })
            ->when($request->input('ordina'), function ($query, $ordina) {
                match ($ordina) {
                    'prezzo_asc' => $query->orderBy('price', 'asc'),
                    'prezzo_desc' => $query->orderBy('price', 'desc'),
                    'titolo' => $query->orderBy('title', 'asc'),
                    default => $query->latest(),
                };
            }, fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $categorie = Category::orderBy('name')->get();

        // ID dei libri già salvati nei preferiti dall'utente (serve per mostrare il cuore pieno o vuoto)
        $preferitiIds = auth()->check() ? auth()->user()->wishlist()->pluck('books.id')->all() : [];

        return view('books.index', compact('libri', 'categorie', 'preferitiIds'));
    }

    /**
     * Scheda di dettaglio di un libro (route bindata sullo slug), con recensioni.
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'author']);
        $book->loadAvg('reviews', 'rating');
        $book->loadCount('reviews');
        $book->load(['reviews' => fn ($q) => $q->with('user')->latest()]);

        $nelPreferiti = auth()->check() && auth()->user()->wishlist()->where('books.id', $book->id)->exists();

        // La recensione già lasciata dall'utente corrente, se esiste (per pre-compilare il form)
        $miaRecensione = auth()->check()
            ? $book->reviews->firstWhere('user_id', auth()->id())
            : null;

        return view('books.show', compact('book', 'nelPreferiti', 'miaRecensione'));
    }

    /**
     * Form di creazione libro (area amministrazione).
     */
    public function create(): View
    {
        $categorie = Category::orderBy('name')->get();
        $autori = Author::orderBy('name')->get();

        return view('books.create', compact('categorie', 'autori'));
    }

    /**
     * Salva un nuovo libro nel database.
     */
    public function store(Request $request): RedirectResponse
    {
        $dati = $this->validaDati($request);
        $dati['slug'] = Str::slug($dati['title']).'-'.uniqid();

        if ($request->hasFile('cover_image')) {
            $dati['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create($dati);

        return redirect()->route('books.show', $book)->with('status', 'Libro creato con successo.');
    }

    /**
     * Form di modifica libro (area amministrazione).
     */
    public function edit(Book $book): View
    {
        $categorie = Category::orderBy('name')->get();
        $autori = Author::orderBy('name')->get();

        return view('books.edit', compact('book', 'categorie', 'autori'));
    }

    /**
     * Aggiorna un libro esistente.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $dati = $this->validaDati($request);

        if ($request->hasFile('cover_image')) {
            // Rimuove la vecchia copertina prima di salvare la nuova
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $dati['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($dati);

        return redirect()->route('books.show', $book)->with('status', 'Libro aggiornato con successo.');
    }

    /**
     * Elimina un libro.
     */
    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('status', 'Libro eliminato.');
    }

    /**
     * Regole di validazione condivise tra store() e update().
     */
    private function validaDati(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'cover_image' => ['nullable', 'image', 'max:2048'], // max 2 MB
        ]);
    }
}
