<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Crea qualche ordine di esempio per l'utente cliente, così la demo mostra subito
     * uno storico ordini, permette di collegare una segnalazione a un ordine reale,
     * e popola con dati realistici la classifica dei "best seller" in home.
     */
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@example.com')->first();

        // Questo è l'ordine "principale" della demo (usato anche per le segnalazioni)
        $this->creaOrdine($cliente, 'il-nome-della-rosa', 1, Order::STATUS_SPEDITO);

        // Altri ordini, solo per dare varietà alla classifica dei più venduti
        $this->creaOrdine($cliente, 'il-nome-della-rosa', 4, Order::STATUS_CONSEGNATO);
        $this->creaOrdine($cliente, 'i-promessi-sposi', 4, Order::STATUS_CONSEGNATO);
        $this->creaOrdine($cliente, 'il-barone-rampante', 3, Order::STATUS_PAGATO);
        $this->creaOrdine($cliente, 'la-forma-dellacqua', 2, Order::STATUS_CONSEGNATO);
        $this->creaOrdine($cliente, 'se-questo-e-un-uomo', 1, Order::STATUS_PAGATO);

        // Un ordine annullato: non deve comparire tra i più venduti
        $this->creaOrdine($cliente, 'la-storia', 10, Order::STATUS_ANNULLATO);
    }

    /**
     * Crea un ordine con una sola riga e aggiorna lo stock del libro coinvolto.
     */
    private function creaOrdine(User $cliente, string $slug, int $quantity, string $status): Order
    {
        $libro = Book::where('slug', $slug)->first();

        $ordine = Order::create([
            'user_id' => $cliente->id,
            'total' => $libro->price * $quantity,
            'status' => $status,
            'shipping_address' => 'Via Roma 10, 00100 Roma (RM)',
            'payment_method' => Order::PAYMENT_CONTRASSEGNO,
        ]);

        $ordine->items()->create([
            'book_id' => $libro->id,
            'quantity' => $quantity,
            'price' => $libro->price, // prezzo congelato al momento dell'ordine
        ]);

        // Un ordine annullato non ha mai davvero impegnato lo stock
        if ($status !== Order::STATUS_ANNULLATO) {
            $libro->decrement('stock', $quantity);
        }

        return $ordine;
    }
}
