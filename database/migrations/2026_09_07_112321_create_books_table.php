<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabella dei libri in vendita
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titolo del libro
            $table->string('slug')->unique(); // Versione URL-friendly del titolo
            $table->text('description')->nullable(); // Descrizione/trama
            $table->decimal('price', 8, 2); // Prezzo in euro (es. 19.99)
            $table->unsignedInteger('stock')->default(0); // Quantità disponibile in magazzino
            $table->string('cover_image')->nullable(); // Percorso immagine di copertina

            // Ogni libro appartiene a una categoria e a un autore
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
