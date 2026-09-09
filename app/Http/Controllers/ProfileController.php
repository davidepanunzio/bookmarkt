<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostra la pagina profilo: dati account, statistiche, ultimi ordini e preferiti.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Statistiche reali sull'attività dell'utente
        $numeroOrdini = $user->orders()->count();
        $totaleSpeso = $user->orders()->where('status', '!=', Order::STATUS_ANNULLATO)->sum('total');
        $numeroPreferiti = $user->wishlist()->count();

        // Ultimi ordini, per l'anteprima nella pagina profilo (lo storico completo resta su "I miei ordini")
        $ultimiOrdini = $user->orders()->latest()->take(5)->get();

        // Ultimi libri salvati nei preferiti (l'elenco completo resta su "Preferiti")
        $preferitiRecenti = $user->wishlist()
            ->with(['category', 'author'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest('wishlists.created_at')
            ->take(4)
            ->get();

        return view('profile.edit', compact(
            'user',
            'numeroOrdini',
            'totaleSpeso',
            'numeroPreferiti',
            'ultimiOrdini',
            'preferitiRecenti',
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
