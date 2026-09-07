<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'book_id', 'quantity', 'price'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // La riga appartiene a un ordine
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // La riga fa riferimento al libro acquistato
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Subtotale della riga: prezzo (congelato al momento dell'ordine) x quantità
    public function subtotale(): float
    {
        return $this->price * $this->quantity;
    }
}
