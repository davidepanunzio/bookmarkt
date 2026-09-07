<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Elenco delle segnalazioni inviate dagli utenti, filtrabile per stato.
     */
    public function index(Request $request): View
    {
        $segnalazioni = Report::query()
            ->with('user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact('segnalazioni'));
    }

    /**
     * Dettaglio di una segnalazione, con form per rispondere e cambiarne lo stato.
     */
    public function show(Report $report): View
    {
        $report->load(['user', 'order']);

        return view('admin.reports.show', compact('report'));
    }

    /**
     * Aggiorna lo stato di una segnalazione e/o salva la risposta dell'amministratore.
     */
    public function update(Request $request, Report $report): RedirectResponse
    {
        $dati = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Report::STATUS_LABELS))],
            'admin_reply' => ['nullable', 'string', 'max:2000'],
        ]);

        // Se l'admin ha scritto (o modificato) una risposta, registriamo anche quando l'ha data
        if (filled($dati['admin_reply'] ?? null)) {
            $dati['replied_at'] = now();
        }

        $report->update($dati);

        return redirect()->route('admin.reports.show', $report)->with('status', 'Segnalazione aggiornata.');
    }
}
