<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Pagina di checkout: riepilogo del carrello + dati di spedizione e pagamento.
     */
    public function checkout(): View|RedirectResponse
    {
        $cart = Cart::with('items.book')->where('user_id', auth()->id())->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Il carrello è vuoto.');
        }

        // Precompila l'indirizzo con quello dell'ultimo ordine, se esiste, per comodità
        $ultimoIndirizzo = auth()->user()->orders()->latest()->value('shipping_address');

        return view('orders.checkout', compact('cart', 'ultimoIndirizzo'));
    }

    /**
     * Conferma il checkout: trasforma il carrello dell'utente in un ordine.
     */
    public function store(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'shipping_address' => ['required', 'string', 'max:255'],
            'payment_method' => ['required', 'in:'.implode(',', array_keys(Order::PAYMENT_LABELS))],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

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
        $order = DB::transaction(function () use ($cart, $dati) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $cart->totale(),
                'status' => Order::STATUS_IN_ATTESA,
                ...$dati,
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
