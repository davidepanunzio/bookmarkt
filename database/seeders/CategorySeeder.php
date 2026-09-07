<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Popola la tabella categories con un set fisso di categorie di libri.
     */
    public function run(): void
    {
        $categorie = ['Narrativa', 'Saggistica', 'Fantascienza', 'Fantasy', 'Giallo', 'Biografie'];

        foreach ($categorie as $nome) {
            Category::create([
                'name' => $nome,
                'slug' => Str::slug($nome),
            ]);
        }
    }
}
