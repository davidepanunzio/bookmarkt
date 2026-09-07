<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Crea un ordine di esempio per l'utente cliente, così la demo mostra subito
     * uno storico ordini e permette di collegare una segnalazione a un ordine reale.
     */
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@example.com')->first();
        $libro = Book::where('slug', 'il-nome-della-rosa')->first();

        $ordine = Order::create([
            'user_id' => $cliente->id,
            'total' => $libro->price,
            'status' => Order::STATUS_SPEDITO,
        ]);

        $ordine->items()->create([
            'book_id' => $libro->id,
            'quantity' => 1,
            'price' => $libro->price, // prezzo congelato al momento dell'ordine
        ]);

        $libro->decrement('stock');
    }
}
