<?php

use App\Models\Book;
use App\Models\Review;
use App\Models\User;

// Rifare una recensione sullo stesso libro deve aggiornarla, non duplicarla
test('una seconda recensione dello stesso utente sullo stesso libro aggiorna quella esistente', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create();

    $this->actingAs($user)->post(route('reviews.store', $libro), [
        'rating' => 5,
        'comment' => 'Bellissimo',
    ]);

    $this->actingAs($user)->post(route('reviews.store', $libro), [
        'rating' => 2,
        'comment' => 'Ripensandoci, non così bello',
    ]);

    expect(Review::count())->toBe(1);
    expect(Review::first()->rating)->toBe(2);
});

test('un utente non può eliminare la recensione di un altro', function () {
    $autore = User::factory()->create();
    $altro = User::factory()->create();
    $libro = Book::factory()->create();
    $recensione = Review::create(['book_id' => $libro->id, 'user_id' => $autore->id, 'rating' => 4]);

    $this->actingAs($altro)
        ->delete(route('reviews.destroy', $recensione))
        ->assertForbidden();

    expect(Review::count())->toBe(1);
});

test('un utente può eliminare la propria recensione', function () {
    $user = User::factory()->create();
    $libro = Book::factory()->create();
    $recensione = Review::create(['book_id' => $libro->id, 'user_id' => $user->id, 'rating' => 4]);

    $this->actingAs($user)
        ->delete(route('reviews.destroy', $recensione))
        ->assertRedirect();

    expect(Review::count())->toBe(0);
});

test('un admin può eliminare la recensione di chiunque', function () {
    $autore = User::factory()->create();
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $libro = Book::factory()->create();
    $recensione = Review::create(['book_id' => $libro->id, 'user_id' => $autore->id, 'rating' => 4]);

    $this->actingAs($admin)
        ->delete(route('reviews.destroy', $recensione))
        ->assertRedirect();

    expect(Review::count())->toBe(0);
});
