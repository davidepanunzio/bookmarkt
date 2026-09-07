<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Mostra i libri salvati tra i preferiti dall'utente autenticato.
     */
    public function index(): View
    {
        $libri = auth()->user()->wishlist()->with(['category', 'author'])->latest('wishlists.created_at')->paginate(12);

        return view('wishlist.index', compact('libri'));
    }

    /**
     * Aggiunge un libro ai preferiti.
     */
    public function store(Book $book): RedirectResponse
    {
        // syncWithoutDetaching evita righe duplicate se il libro è già nei preferiti
        auth()->user()->wishlist()->syncWithoutDetaching([$book->id]);

        return back()->with('status', 'Libro aggiunto ai preferiti.');
    }

    /**
     * Rimuove un libro dai preferiti.
     */
    public function destroy(Book $book): RedirectResponse
    {
        auth()->user()->wishlist()->detach($book->id);

        return back()->with('status', 'Libro rimosso dai preferiti.');
    }
}
