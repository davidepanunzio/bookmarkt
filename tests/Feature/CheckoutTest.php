<?php

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

// Checkout con esito positivo: crea l'ordine, congela il prezzo, scala lo stock e svuota il carrello
test('il checkout completa l\'ordine e aggiorna lo stock', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create(['price' => 10.00, 'stock' => 5]);

    $this->actingAs($user)
        ->post(route('cart.store', $libro), ['quantity' => 2])
        ->assertRedirect();

    $this->actingAs($user)
        ->post(route('orders.store'))
        ->assertRedirect();

    $ordine = Order::first();

    expect($ordine)->not->toBeNull();
    expect((float) $ordine->total)->toBe(20.00); // 10.00 x 2
    expect($ordine->status)->toBe(Order::STATUS_IN_ATTESA);
    expect($ordine->items)->toHaveCount(1);
    expect((float) $ordine->items->first()->price)->toBe(10.00); // prezzo congelato

    $libro->refresh();
    expect($libro->stock)->toBe(3); // 5 - 2

    expect($user->cart->items()->count())->toBe(0); // carrello svuotato
});

// Il checkout con carrello vuoto non deve creare nessun ordine
test('il checkout con carrello vuoto non crea un ordine', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('orders.store'))
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

    $this->actingAs($user)->post(route('orders.store'))
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
    expect($user->cart->items()->count())->toBe(1); // il carrello resta intatto
});
