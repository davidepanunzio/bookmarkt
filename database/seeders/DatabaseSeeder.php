<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Utente amministratore: può gestire il catalogo (libri, categorie, autori)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
        ]);

        // Utente cliente: può solo navigare il catalogo, comprare, vedere i suoi ordini
        User::factory()->create([
            'name' => 'Cliente Prova',
            'email' => 'cliente@example.com',
            'role' => User::ROLE_USER,
        ]);

        // Popola il catalogo con dati di esempio (ordine importante: categorie e autori prima dei libri,
        // le recensioni per ultime perché fanno riferimento sia ai libri che agli utenti)
        $this->call([
            CategorySeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
