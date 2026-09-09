<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Popola la tabella authors con un set fisso di autori (dati di esempio per la demo).
     */
    public function run(): void
    {
        $autori = [
            ['name' => 'Italo Calvino', 'bio' => 'Scrittore italiano del Novecento, noto per romanzi come "Il barone rampante".'],
            ['name' => 'Umberto Eco', 'bio' => 'Scrittore e semiologo italiano, autore de "Il nome della rosa".'],
            ['name' => 'Andrea Camilleri', 'bio' => 'Scrittore siciliano, creatore del commissario Montalbano.'],
            ['name' => 'Primo Levi', 'bio' => 'Scrittore e chimico italiano, autore di memorie e romanzi.'],
            ['name' => 'Elsa Morante', 'bio' => 'Scrittrice italiana, autrice di "La storia".'],
            ['name' => 'Alessandro Manzoni', 'bio' => 'Scrittore e poeta italiano, autore de "I promessi sposi".'],
            ['name' => 'Licia Troisi', 'bio' => 'Scrittrice italiana di fantasy, autrice della saga "Cronache del mondo emerso".'],
        ];

        foreach ($autori as $autore) {
            Author::create($autore);
        }
    }
}
