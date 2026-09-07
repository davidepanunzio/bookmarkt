<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Mostra il carrello dell'utente autenticato.
     */
    public function index(): View
    {
        $cart = $this->carrelloUtente();
        $cart->load('items.book');

        return view('cart.index', compact('cart'));
    }

    /**
     * Aggiunge un libro al carrello (o ne aumenta la quantità se già presente).
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $request->input('quantity', 1);
        $cart = $this->carrelloUtente();

        $item = $cart->items()->where('book_id', $book->id)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'book_id' => $book->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('status', 'Libro aggiunto al carrello.');
    }

    /**
     * Aggiorna la quantità di una riga del carrello.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->verificaProprietario($cartItem);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update(['quantity' => $request->input('quantity')]);

        return back()->with('status', 'Carrello aggiornato.');
    }

    /**
     * Rimuove una riga dal carrello.
     */
    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->verificaProprietario($cartItem);

        $cartItem->delete();

        return back()->with('status', 'Libro rimosso dal carrello.');
    }

    /**
     * Recupera (creandolo se necessario) il carrello dell'utente autenticato.
     */
    private function carrelloUtente(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    /**
     * Impedisce a un utente di modificare righe di carrello altrui.
     */
    private function verificaProprietario(CartItem $cartItem): void
    {
        abort_if($cartItem->cart->user_id !== auth()->id(), 403);
    }
}
