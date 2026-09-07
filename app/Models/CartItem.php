<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'book_id', 'quantity'];

    // La riga appartiene a un carrello
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    // La riga fa riferimento a un libro
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // Subtotale della riga: prezzo del libro x quantità
    public function subtotale(): float
    {
        return $this->book->price * $this->quantity;
    }
}
