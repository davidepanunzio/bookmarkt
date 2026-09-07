<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Storico degli ordini dell'utente autenticato.
     */
    public function index(): View
    {
        $ordini = auth()->user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('ordini'));
    }

    /**
     * Dettaglio di un ordine (solo il proprietario può vederlo).
     */
    public function show(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items.book');

        return view('orders.show', compact('order'));
    }

    /**
     * Checkout: trasforma il carrello dell'utente in un ordine.
     */
    public function store(): RedirectResponse
    {
        $cart = Cart::with('items.book')->where('user_id', auth()->id())->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Il carrello è vuoto.');
        }

        // Verifica che ci sia abbastanza disponibilità per ogni libro prima di procedere
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->book->stock) {
                return redirect()->route('cart.index')
                    ->with('status', "Disponibilità insufficiente per \"{$item->book->title}\".");
            }
        }

        // Tutte le operazioni avvengono in un'unica transazione: o vanno a buon fine tutte, o nessuna
        $order = DB::transaction(function () use ($cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $cart->totale(),
                'status' => Order::STATUS_IN_ATTESA,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price, // prezzo "congelato" al momento dell'acquisto
                ]);

                $item->book->decrement('stock', $item->quantity);
            }

            // Svuota il carrello dopo il checkout
            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('status', 'Ordine effettuato con successo.');
    }
}
