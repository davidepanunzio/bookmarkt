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
        Schema::table('orders', function (Blueprint $table) {
            // Indirizzo a cui spedire l'ordine
            $table->string('shipping_address')->after('user_id');
            // Come il cliente pagherà: contrassegno o bonifico (nessun vero gateway di pagamento)
            $table->string('payment_method')->after('shipping_address');
            // Eventuale nota per il corriere/il negozio
            $table->text('note')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'payment_method', 'note']);
        });
    }
};
