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
            [
                'title' => 'Il barone rampante',
                'author' => 'Italo Calvino',
                'category' => 'Narrativa',
                'price' => 12.50,
                'stock' => 20,
                'description' => 'A dodici anni, dopo un litigio con il padre, Cosimo Piovasco di Rondò decide di arrampicarsi su un albero e di non scendere mai più. Da lassù, passando da una pianta all\'altra, osserverà il mondo e vivrà una vita intera fatta di amori, letture e imprese, senza mai perdere il contatto con la realtà che si è lasciato sotto i piedi.',
            ],
            [
                'title' => 'Le cosmicomiche',
                'author' => 'Italo Calvino',
                'category' => 'Fantascienza',
                'price' => 11.90,
                'stock' => 15,
                'description' => 'Una raccolta di racconti narrati da Qfwfq, testimone immaginario dei grandi eventi cosmici: la nascita della Luna, la formazione dei colori, l\'estinzione dei dinosauri. Calvino trasforma le teorie scientifiche in fiabe surreali e ironiche sull\'origine dell\'universo.',
            ],
            [
                'title' => 'Il nome della rosa',
                'author' => 'Umberto Eco',
                'category' => 'Giallo',
                'price' => 14.90,
                'stock' => 25,
                'description' => 'Nel 1327, il frate francescano Guglielmo da Baskerville e il suo giovane novizio Adso arrivano in un\'abbazia benedettina sconvolta da una serie di morti misteriose. Tra biblioteche labirintiche, manoscritti proibiti e intrighi teologici, Guglielmo dovrà usare la logica per svelare la verità prima che la Chiesa la seppellisca per sempre.',
            ],
            [
                'title' => 'Il pendolo di Foucault',
                'author' => 'Umberto Eco',
                'category' => 'Narrativa',
                'price' => 13.50,
                'stock' => 10,
                'description' => 'Tre editori annoiati si divertono a costruire, al computer, un piano cospirativo immaginario che intreccia templari, alchimisti e società segrete di ogni epoca. Ma il gioco sfugge di mano: qualcuno inizia a crederci sul serio, e il confine tra finzione e realtà si fa sempre più pericoloso.',
            ],
            [
                'title' => 'Come si fa una tesi di laurea',
                'author' => 'Umberto Eco',
                'category' => 'Saggistica',
                'price' => 16.00,
                'stock' => 8,
                'description' => 'Un manuale pratico che accompagna lo studente in ogni fase della tesi: dalla scelta dell\'argomento alla ricerca bibliografica, dalla schedatura delle fonti alla stesura finale. Da decenni un punto di riferimento per chi si avvicina per la prima volta al metodo della ricerca accademica.',
            ],
            [
                'title' => 'La forma dell\'acqua',
                'author' => 'Andrea Camilleri',
                'category' => 'Giallo',
                'price' => 10.90,
                'stock' => 30,
                'description' => 'Il cadavere dell\'ingegner Luparello viene trovato in un luogo equivoco alla periferia di Vigàta. Il commissario Montalbano, insospettito dai troppi dettagli che non tornano, indaga tra interessi politici ed economici in quella che è la prima avventura della celebre serie.',
            ],
            [
                'title' => 'Se questo è un uomo',
                'author' => 'Primo Levi',
                'category' => 'Biografie',
                'price' => 9.90,
                'stock' => 18,
                'description' => 'La testimonianza diretta di Primo Levi sulla propria deportazione ad Auschwitz: un resoconto lucido e senza sconti sulla vita nel campo di concentramento, sulla fatica di sopravvivere e sulla lotta quotidiana per non perdere la propria umanità.',
            ],
            [
                'title' => 'La storia',
                'author' => 'Elsa Morante',
                'category' => 'Narrativa',
                'price' => 15.50,
                'stock' => 12,
                'description' => 'Roma, Seconda guerra mondiale: la maestra Ida Ramundo e i suoi due figli, Nino e il piccolo Useppe, attraversano gli anni del conflitto travolti da eventi più grandi di loro. Un romanzo che intreccia la grande Storia con le storie minime di chi la subisce senza poterla scegliere.',
            ],
            [
                'title' => 'I promessi sposi',
                'author' => 'Alessandro Manzoni',
                'category' => 'Narrativa',
                'price' => 8.90,
                'stock' => 40,
                'description' => 'Nella Lombardia del Seicento, i giovani promessi sposi Renzo e Lucia vengono separati dalle prepotenze del signorotto locale don Rodrigo. Tra fughe, carestie, guerre e la grande peste di Milano, i due dovranno affrontare un lungo percorso prima di potersi finalmente ricongiungere.',
            ],
            [
                'title' => 'Nihal della Terra del Vento',
                'author' => 'Licia Troisi',
                'category' => 'Fantasy',
                'price' => 13.90,
                'stock' => 20,
                'description' => 'Cresciuta come un maschio tra le mura di una fortezza, Nihal è l\'ultima elfa del Vento rimasta nel Mondo Emerso. Quando il suo mondo di adozione viene minacciato dal terribile Tiranno, dovrà imparare a conoscere se stessa e trovare il coraggio di combattere per ciò che ama, nel primo capitolo delle Cronache del Mondo Emerso.',
            ],
            [
                'title' => 'La ragazza drago',
                'author' => 'Licia Troisi',
                'category' => 'Fantasy',
                'price' => 15.90,
                'stock' => 15,
                'description' => 'Sulis vive isolata nella foresta insieme al padre, ignara delle proprie origini. Una serie di eventi sconvolgerà la sua esistenza tranquilla, rivelandole un legame ancestrale con i draghi e un destino molto più grande di lei nel regno di Rondine.',
            ],
        ];

        foreach ($libri as $libro) {
            $slug = Str::slug($libro['title']);

            Book::create([
                'title' => $libro['title'],
                'slug' => $slug,
                'description' => $libro['description'],
                'price' => $libro['price'],
                'stock' => $libro['stock'],
                'category_id' => Category::where('name', $libro['category'])->first()->id,
                'author_id' => Author::where('name', $libro['author'])->first()->id,
                // Copertina reale già presente in storage/app/public/covers (vedi cartella "covers")
                'cover_image' => 'covers/'.$slug.'.png',
            ]);
        }
    }
}
