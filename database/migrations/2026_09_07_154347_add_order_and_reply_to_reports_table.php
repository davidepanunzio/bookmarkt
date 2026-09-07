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
        Schema::table('reports', function (Blueprint $table) {
            // Ordine a cui la segnalazione fa riferimento (facoltativo)
            $table->foreignId('order_id')->nullable()->after('user_id')->constrained()->nullOnDelete();

            // Risposta dell'amministratore alla segnalazione
            $table->text('admin_reply')->nullable()->after('message');
            $table->timestamp('replied_at')->nullable()->after('admin_reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
            $table->dropColumn(['admin_reply', 'replied_at']);
        });
    }
};
