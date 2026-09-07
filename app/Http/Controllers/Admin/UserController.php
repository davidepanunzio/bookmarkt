<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Elenco degli utenti registrati, con ricerca e filtro per ruolo.
     */
    public function index(Request $request): View
    {
        $utenti = User::query()
            ->withCount(['orders', 'reviews'])
            ->when($request->filled('cerca'), function ($query) use ($request) {
                $testo = $request->input('cerca');
                $query->where(fn ($q) => $q->where('name', 'like', "%{$testo}%")->orWhere('email', 'like', "%{$testo}%"));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('utenti'));
    }

    /**
     * Scheda di un utente: dati, ordini effettuati e recensioni scritte.
     */
    public function show(User $user): View
    {
        $user->load(['orders' => fn ($q) => $q->latest()]);
        $user->load(['reviews' => fn ($q) => $q->with('book')->latest()]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Cambia il ruolo di un utente (utente <-> admin).
     * Un amministratore non può modificare il proprio ruolo da qui, per non rischiare di autoescludersi.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 403, 'Non puoi modificare il tuo stesso ruolo.');

        $dati = $request->validate([
            'role' => ['required', 'in:'.User::ROLE_USER.','.User::ROLE_ADMIN],
        ]);

        $user->update($dati);

        return redirect()->route('admin.users.show', $user)->with('status', 'Ruolo aggiornato.');
    }
}
