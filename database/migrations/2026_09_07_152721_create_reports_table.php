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
        // Segnalazioni/messaggi che gli utenti inviano agli amministratori (es. problemi riscontrati)
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subject'); // Oggetto della segnalazione
            $table->text('message'); // Testo del messaggio
            // Stato della segnalazione: nuovo -> in_lavorazione -> risolto
            $table->string('status')->default('nuovo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
