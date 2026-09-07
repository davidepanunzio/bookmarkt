<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Elenco di tutte le recensioni del negozio, per moderazione.
     * L'eliminazione riusa la rotta pubblica reviews.destroy (già autorizzata per gli admin).
     */
    public function index(Request $request): View
    {
        $recensioni = Review::query()
            ->with(['book', 'user'])
            ->when($request->filled('cerca'), function ($query) use ($request) {
                $testo = $request->input('cerca');
                $query->whereHas('book', fn ($q) => $q->where('title', 'like', "%{$testo}%"));
            })
            ->when($request->filled('rating'), function ($query) use ($request) {
                $query->where('rating', $request->input('rating'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', compact('recensioni'));
    }
}
