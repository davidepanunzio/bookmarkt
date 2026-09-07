<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Popola la tabella reviews con alcune recensioni di esempio,
     * per mostrare subito voti medi e commenti nella demo.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $cliente = User::where('email', 'cliente@example.com')->first();

        $recensioni = [
            ['libro' => 'Il nome della rosa', 'utente' => $cliente, 'rating' => 5, 'comment' => 'Un giallo intelligentissimo, ambientato in un\'abbazia medievale. Da leggere.'],
            ['libro' => 'Il nome della rosa', 'utente' => $admin, 'rating' => 4, 'comment' => 'Impegnativo ma vale la pena, soprattutto per gli amanti della filosofia.'],
            ['libro' => 'Il barone rampante', 'utente' => $cliente, 'rating' => 4, 'comment' => 'Uno stile leggero per raccontare una scelta di vita radicale.'],
            ['libro' => 'I promessi sposi', 'utente' => $admin, 'rating' => 3, 'comment' => 'Un classico della letteratura italiana, un po\' lento in alcuni tratti.'],
        ];

        foreach ($recensioni as $r) {
            Review::create([
                'book_id' => Book::where('title', $r['libro'])->first()->id,
                'user_id' => $r['utente']->id,
                'rating' => $r['rating'],
                'comment' => $r['comment'],
            ]);
        }
    }
}
