<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Salva la recensione di un libro da parte dell'utente autenticato.
     * Se l'utente ha già recensito il libro, la recensione viene aggiornata (non duplicata).
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $dati = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['book_id' => $book->id, 'user_id' => auth()->id()],
            $dati
        );

        return back()->with('status', 'Recensione salvata, grazie!');
    }

    /**
     * Elimina una recensione: può farlo solo l'autore o un amministratore.
     */
    public function destroy(Review $review): RedirectResponse
    {
        abort_if($review->user_id !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $review->delete();

        return back()->with('status', 'Recensione eliminata.');
    }
}
