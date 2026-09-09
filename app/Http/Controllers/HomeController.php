<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home page pubblica: carosello con gli ultimi arrivi, categorie, ultime uscite e best seller.
     */
    public function index(): View
    {
        // Il carosello mostra i libri più recenti (al massimo 5)
        $libriHero = Book::with(['category', 'author'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(5)
            ->get();

        // "Ultime uscite": i successivi arrivi, esclusi quelli già mostrati nel carosello
        // (esattamente 4, per riempire una sola riga della griglia a 4 colonne)
        $ultimiLibri = Book::with(['category', 'author'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereNotIn('id', $libriHero->pluck('id'))
            ->latest()
            ->take(4)
            ->get();

        $categorie = Category::orderBy('name')->get();

        // Classifica dei più venduti: somma delle quantità nelle righe d'ordine, ordini annullati esclusi
        $bestSeller = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', Order::STATUS_ANNULLATO)
            ->selectRaw('order_items.book_id, SUM(order_items.quantity) as venduti')
            ->with(['book.category', 'book.author'])
            ->groupBy('order_items.book_id')
            ->orderByDesc('venduti')
            ->take(5)
            ->get();

        $preferitiIds = auth()->check() ? auth()->user()->wishlist()->pluck('books.id')->all() : [];

        return view('home', compact('libriHero', 'ultimiLibri', 'categorie', 'bestSeller', 'preferitiIds'));
    }
}
