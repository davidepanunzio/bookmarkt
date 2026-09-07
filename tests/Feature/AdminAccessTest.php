<?php

use App\Models\User;

test('un ospite non autenticato viene rediretto al login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('un cliente normale non può accedere al pannello admin', function () {
    $cliente = User::factory()->create(['role' => User::ROLE_USER]);

    $this->actingAs($cliente)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('un amministratore può accedere al pannello admin', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

test('un cliente normale non può creare libri, categorie o autori', function () {
    $cliente = User::factory()->create(['role' => User::ROLE_USER]);

    $this->actingAs($cliente)->get(route('admin.books.create'))->assertForbidden();
    $this->actingAs($cliente)->get(route('admin.categories.create'))->assertForbidden();
    $this->actingAs($cliente)->get(route('admin.authors.create'))->assertForbidden();
});
