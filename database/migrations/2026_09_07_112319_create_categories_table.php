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
        // Tabella delle categorie di libri (es. Narrativa, Saggistica, Fantascienza)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della categoria
            $table->string('slug')->unique(); // Versione URL-friendly del nome
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
