<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    // Il carrello appartiene a un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Il carrello contiene più righe (una per ogni libro aggiunto)
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Totale del carrello: somma di prezzo x quantità per ogni riga
    public function totale(): float
    {
        return $this->items->sum(fn (CartItem $item) => $item->book->price * $item->quantity);
    }
}
