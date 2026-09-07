<?php

use App\Models\User;

test('un cliente normale non può accedere alla gestione utenti', function () {
    $cliente = User::factory()->create(['role' => User::ROLE_USER]);

    $this->actingAs($cliente)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('un admin può vedere l\'elenco e la scheda di un utente', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $cliente = User::factory()->create(['name' => 'Mario Rossi']);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Mario Rossi');

    $this->actingAs($admin)
        ->get(route('admin.users.show', $cliente))
        ->assertOk();
});

test('un admin può promuovere un cliente ad admin', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $cliente = User::factory()->create(['role' => User::ROLE_USER]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $cliente), ['role' => User::ROLE_ADMIN])
        ->assertRedirect();

    expect($cliente->fresh()->isAdmin())->toBeTrue();
});

test('un admin non può modificare il proprio ruolo', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->put(route('admin.users.update', $admin), ['role' => User::ROLE_USER])
        ->assertForbidden();

    expect($admin->fresh()->isAdmin())->toBeTrue();
});
