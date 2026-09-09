<?php

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

// Dati di spedizione/pagamento validi, riusati in più test
function datiCheckoutValidi(): array
{
    return [
        'shipping_address' => 'Via Roma 1, 00100 Roma (RM)',
        'payment_method' => Order::PAYMENT_CONTRASSEGNO,
        'note' => 'Citofonare Rossi',
    ];
}

// La pagina di checkout mostra il riepilogo del carrello
test('la pagina di checkout mostra i libri nel carrello', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create(['title' => 'Il libro di prova']);

    $this->actingAs($user)->post(route('cart.store', $libro), ['quantity' => 1]);

    $this->actingAs($user)->get(route('orders.checkout'))
        ->assertOk()
        ->assertSee('Il libro di prova');
});

// Con il carrello vuoto, il checkout reindirizza al carrello
test('il checkout con carrello vuoto reindirizza al carrello', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('orders.checkout'))
        ->assertRedirect(route('cart.index'));
});

// Checkout con esito positivo: crea l'ordine, congela il prezzo, scala lo stock e svuota il carrello
test('il checkout completa l\'ordine e aggiorna lo stock', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create(['price' => 10.00, 'stock' => 5]);

    $this->actingAs($user)
        ->post(route('cart.store', $libro), ['quantity' => 2])
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('orders.store'), datiCheckoutValidi())
        ->assertRedirect();

    $ordine = Order::first();

    expect($ordine)->not->toBeNull();
    expect((float) $ordine->total)->toBe(20.00); // 10.00 x 2
    expect($ordine->status)->toBe(Order::STATUS_IN_ATTESA);
    expect($ordine->shipping_address)->toBe('Via Roma 1, 00100 Roma (RM)');
    expect($ordine->payment_method)->toBe(Order::PAYMENT_CONTRASSEGNO);
    expect($ordine->items)->toHaveCount(1);
    expect((float) $ordine->items->first()->price)->toBe(10.00); // prezzo congelato

    $libro->refresh();
    expect($libro->stock)->toBe(3); // 5 - 2

    expect($user->cart->items()->count())->toBe(0); // carrello svuotato
});

// Senza indirizzo di spedizione o metodo di pagamento, il checkout non va a buon fine
test('il checkout richiede indirizzo di spedizione e metodo di pagamento', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create(['price' => 10.00, 'stock' => 5]);

    $this->actingAs($user)->post(route('cart.store', $libro), ['quantity' => 1]);

    $this->actingAs($user)
        ->post(route('orders.store'), []) // nessun dato inviato
        ->assertSessionHasErrors(['shipping_address', 'payment_method']);

    expect(Order::count())->toBe(0);
});

// Il checkout con carrello vuoto non deve creare nessun ordine
test('il checkout con carrello vuoto non crea un ordine', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('orders.store'), datiCheckoutValidi())
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
});

// Se lo stock non basta, il checkout viene bloccato e nulla viene modificato
test('il checkout blocca se lo stock è insufficiente', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create(['price' => 10.00, 'stock' => 1]);

    $this->actingAs($user)->post(route('cart.store', $libro), ['quantity' => 1]);

    // Qualcun altro (o un altro browser) fa scendere lo stock a 0 prima del checkout
    $libro->update(['stock' => 0]);

    $this->actingAs($user)->post(route('orders.store'), datiCheckoutValidi())
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
    expect($user->cart->items()->count())->toBe(1); // il carrello resta intatto
});
