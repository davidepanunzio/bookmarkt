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
        // Tabella degli autori dei libri
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome e cognome dell'autore
            $table->text('bio')->nullable(); // Breve biografia (opzionale)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
