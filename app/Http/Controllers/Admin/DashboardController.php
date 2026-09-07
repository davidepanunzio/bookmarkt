<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Report;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Pannello con le statistiche principali del negozio (solo per admin).
     */
    public function index(): View
    {
        // Conteggi generali sul catalogo e sugli utenti
        $numeroLibri = Book::count();
        $numeroCategorie = Category::count();
        $numeroAutori = Author::count();
        $numeroClienti = User::where('role', User::ROLE_USER)->count();

        // Ordini: totale, fatturato e suddivisione per stato
        $numeroOrdini = Order::count();
        $fatturatoTotale = Order::where('status', '!=', Order::STATUS_ANNULLATO)->sum('total');

        $ordiniPerStato = Order::query()
            ->selectRaw('status, count(*) as totale')
            ->groupBy('status')
            ->pluck('totale', 'status');

        // I 5 libri più venduti, calcolati sommando le quantità nelle righe d'ordine
        $libriPiuVenduti = OrderItem::query()
            ->selectRaw('book_id, SUM(quantity) as venduti')
            ->with('book')
            ->groupBy('book_id')
            ->orderByDesc('venduti')
            ->take(5)
            ->get();

        // Gli ultimi 5 ordini ricevuti, per un controllo rapido
        $ultimiOrdini = Order::with('user')->latest()->take(5)->get();

        // Segnalazioni degli utenti ancora da esaminare
        $segnalazioniNuove = Report::where('status', Report::STATUS_NUOVO)->count();

        return view('admin.dashboard', compact(
            'numeroLibri',
            'numeroCategorie',
            'numeroAutori',
            'numeroClienti',
            'numeroOrdini',
            'fatturatoTotale',
            'ordiniPerStato',
            'libriPiuVenduti',
            'ultimiOrdini',
            'segnalazioniNuove',
        ));
    }
}
