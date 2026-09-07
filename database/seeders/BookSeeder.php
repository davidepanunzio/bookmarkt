<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Popola la tabella books con alcuni libri di esempio, collegati a categorie e autori già creati.
     */
    public function run(): void
    {
        $libri = [
            ['title' => 'Il barone rampante', 'author' => 'Italo Calvino', 'category' => 'Narrativa', 'price' => 12.50, 'stock' => 20],
            ['title' => 'Le cosmicomiche', 'author' => 'Italo Calvino', 'category' => 'Fantascienza', 'price' => 11.90, 'stock' => 15],
            ['title' => 'Il nome della rosa', 'author' => 'Umberto Eco', 'category' => 'Giallo', 'price' => 14.90, 'stock' => 25],
            ['title' => 'Il pendolo di Foucault', 'author' => 'Umberto Eco', 'category' => 'Narrativa', 'price' => 13.50, 'stock' => 10],
            ['title' => 'Come si fa una tesi di laurea', 'author' => 'Umberto Eco', 'category' => 'Saggistica', 'price' => 16.00, 'stock' => 8],
            ['title' => 'La forma dell\'acqua', 'author' => 'Andrea Camilleri', 'category' => 'Giallo', 'price' => 10.90, 'stock' => 30],
            ['title' => 'Se questo è un uomo', 'author' => 'Primo Levi', 'category' => 'Biografie', 'price' => 9.90, 'stock' => 18],
            ['title' => 'La storia', 'author' => 'Elsa Morante', 'category' => 'Narrativa', 'price' => 15.50, 'stock' => 12],
            ['title' => 'I promessi sposi', 'author' => 'Alessandro Manzoni', 'category' => 'Narrativa', 'price' => 8.90, 'stock' => 40],
        ];

        foreach ($libri as $libro) {
            Book::create([
                'title' => $libro['title'],
                'slug' => Str::slug($libro['title']),
                'description' => 'Descrizione di esempio per "'.$libro['title'].'". Testo segnaposto da sostituire con la trama reale.',
                'price' => $libro['price'],
                'stock' => $libro['stock'],
                'category_id' => Category::where('name', $libro['category'])->first()->id,
                'author_id' => Author::where('name', $libro['author'])->first()->id,
            ]);
        }
    }
}
