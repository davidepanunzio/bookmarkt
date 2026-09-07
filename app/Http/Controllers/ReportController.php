<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Elenco delle segnalazioni inviate dall'utente autenticato ("Le mie segnalazioni").
     */
    public function index(): View
    {
        $segnalazioni = auth()->user()->reports()->latest()->paginate(10);

        return view('reports.index', compact('segnalazioni'));
    }

    /**
     * Form per contattare gli amministratori (segnalare un problema).
     */
    public function create(): View
    {
        // Solo gli ordini dell'utente, per poterne collegare uno alla segnalazione (facoltativo)
        $ordini = auth()->user()->orders()->latest()->get();

        return view('reports.create', compact('ordini'));
    }

    /**
     * Salva la segnalazione inviata dall'utente.
     */
    public function store(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            // L'ordine, se indicato, deve appartenere all'utente che sta scrivendo
            'order_id' => ['nullable', Rule::exists('orders', 'id')->where('user_id', auth()->id())],
        ]);

        $report = auth()->user()->reports()->create([
            ...$dati,
            'status' => Report::STATUS_NUOVO,
        ]);

        return redirect()->route('reports.show', $report)->with('status', 'Segnalazione inviata: un amministratore la esaminerà al più presto.');
    }

    /**
     * Dettaglio di una segnalazione (solo il proprietario può vederla).
     */
    public function show(Report $report): View
    {
        abort_if($report->user_id !== auth()->id(), 403);

        $report->load('order');

        return view('reports.show', compact('report'));
    }
}
