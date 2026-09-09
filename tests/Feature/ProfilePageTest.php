<?php

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

test('la pagina profilo mostra le statistiche reali dell\'utente', function () {
    $user = User::factory()->create();

    Order::factory()->create(['user_id' => $user->id, 'total' => 20.00, 'status' => Order::STATUS_CONSEGNATO]);
    Order::factory()->create(['user_id' => $user->id, 'total' => 15.50, 'status' => Order::STATUS_IN_ATTESA]);
    // Un ordine annullato non deve contare nel totale speso
    Order::factory()->create(['user_id' => $user->id, 'total' => 100.00, 'status' => Order::STATUS_ANNULLATO]);

    $libro = Book::factory()->create();
    $user->wishlist()->attach($libro->id);

    $response = $this->actingAs($user)->get(route('profile.edit'));

    // Il totale speso esclude l'ordine annullato (20.00 + 15.50, non 115.50)
    $response->assertOk()->assertSee('35,50');
});

test('la pagina profilo mostra gli ordini recenti e i preferiti dell\'utente', function () {
    $user = User::factory()->create();
    $ordine = Order::factory()->create(['user_id' => $user->id]);
    $libro = Book::factory()->create(['title' => 'Libro nei preferiti']);
    $user->wishlist()->attach($libro->id);

    $this->actingAs($user)->get(route('profile.edit'))
        ->assertSee('Ordine #'.$ordine->id)
        ->assertSee('Libro nei preferiti');
});
