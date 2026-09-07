<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Elenco di tutti gli ordini del negozio (non solo quelli dell'admin), con filtro per stato.
     */
    public function index(Request $request): View
    {
        $ordini = Order::query()
            ->with('user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('ordini'));
    }

    /**
     * Dettaglio di un ordine, con form per cambiarne lo stato.
     */
    public function show(Order $order): View
    {
        $order->load('items.book', 'user');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Aggiorna lo stato di un ordine (es. da "pagato" a "spedito").
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $dati = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
        ]);

        $order->update($dati);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Stato dell\'ordine aggiornato.');
    }
}
