<?php

use App\Models\Order;
use App\Models\Report;
use App\Models\User;

test('un utente autenticato può inviare una segnalazione', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('reports.store'), [
        'subject' => 'Problema con il pagamento',
        'message' => 'Non riesco a completare il checkout.',
    ])->assertRedirect();

    expect(Report::count())->toBe(1);
    $segnalazione = Report::first();
    expect($segnalazione->user_id)->toBe($user->id);
    expect($segnalazione->status)->toBe(Report::STATUS_NUOVO);
});

test('un ospite non può inviare una segnalazione', function () {
    $this->post(route('reports.store'), [
        'subject' => 'Test',
        'message' => 'Test',
    ])->assertRedirect(route('login'));

    expect(Report::count())->toBe(0);
});

test('un admin non può usare la funzione contattaci riservata agli utenti', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('reports.index'))->assertForbidden();
    $this->actingAs($admin)->get(route('reports.create'))->assertForbidden();

    $this->actingAs($admin)->post(route('reports.store'), [
        'subject' => 'Test',
        'message' => 'Test',
    ])->assertForbidden();

    expect(Report::count())->toBe(0);
});

test('un cliente normale non può vedere le segnalazioni nel pannello admin', function () {
    $cliente = User::factory()->create(['role' => User::ROLE_USER]);

    $this->actingAs($cliente)
        ->get(route('admin.reports.index'))
        ->assertForbidden();
});

test('un admin può vedere ed evadere una segnalazione', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $utente = User::factory()->create();
    $segnalazione = Report::create([
        'user_id' => $utente->id,
        'subject' => 'Bug nel carrello',
        'message' => 'Il totale non si aggiorna.',
        'status' => Report::STATUS_NUOVO,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.reports.index'))
        ->assertOk()
        ->assertSee('Bug nel carrello');

    $this->actingAs($admin)
        ->put(route('admin.reports.update', $segnalazione), ['status' => Report::STATUS_RISOLTO])
        ->assertRedirect();

    expect($segnalazione->fresh()->status)->toBe(Report::STATUS_RISOLTO);
});

test('un utente può collegare solo un proprio ordine alla segnalazione', function () {
    $user = User::factory()->create();
    $altroUtente = User::factory()->create();
    $ordineAltrui = Order::factory()->create(['user_id' => $altroUtente->id]);

    // Prova a collegare l'ordine di un altro utente: la richiesta deve fallire in validazione
    $this->actingAs($user)->post(route('reports.store'), [
        'subject' => 'Problema',
        'message' => 'Messaggio di prova',
        'order_id' => $ordineAltrui->id,
    ])->assertSessionHasErrors('order_id');

    expect(Report::count())->toBe(0);

    // Con il proprio ordine invece funziona
    $proprioOrdine = Order::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user)->post(route('reports.store'), [
        'subject' => 'Problema',
        'message' => 'Messaggio di prova',
        'order_id' => $proprioOrdine->id,
    ])->assertRedirect();

    expect(Report::first()->order_id)->toBe($proprioOrdine->id);
});

test('un utente vede le proprie segnalazioni e la risposta admin, non quelle altrui', function () {
    $user = User::factory()->create();
    $altroUtente = User::factory()->create();

    $miaSegnalazione = Report::create([
        'user_id' => $user->id,
        'subject' => 'La mia segnalazione',
        'message' => 'Messaggio',
        'status' => Report::STATUS_RISOLTO,
        'admin_reply' => 'Risolto, grazie della segnalazione',
        'replied_at' => now(),
    ]);
    $segnalazioneAltrui = Report::create([
        'user_id' => $altroUtente->id,
        'subject' => 'Segnalazione di un altro utente',
        'message' => 'Messaggio',
        'status' => Report::STATUS_NUOVO,
    ]);

    // L'elenco mostra solo le proprie
    $this->actingAs($user)->get(route('reports.index'))
        ->assertSee('La mia segnalazione')
        ->assertDontSee('Segnalazione di un altro utente');

    // Il dettaglio mostra la risposta
    $this->actingAs($user)->get(route('reports.show', $miaSegnalazione))
        ->assertOk()
        ->assertSee('Risolto, grazie della segnalazione');

    // Non può vedere il dettaglio della segnalazione di un altro utente
    $this->actingAs($user)->get(route('reports.show', $segnalazioneAltrui))
        ->assertForbidden();
});

test('un admin può rispondere a una segnalazione', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $utente = User::factory()->create();
    $segnalazione = Report::create([
        'user_id' => $utente->id,
        'subject' => 'Bug nel carrello',
        'message' => 'Il totale non si aggiorna.',
        'status' => Report::STATUS_NUOVO,
    ]);

    $this->actingAs($admin)->put(route('admin.reports.update', $segnalazione), [
        'status' => Report::STATUS_RISOLTO,
        'admin_reply' => 'Abbiamo corretto il bug, grazie per la segnalazione!',
    ])->assertRedirect();

    $segnalazione->refresh();
    expect($segnalazione->admin_reply)->toBe('Abbiamo corretto il bug, grazie per la segnalazione!');
    expect($segnalazione->replied_at)->not->toBeNull();
});
