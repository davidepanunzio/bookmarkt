<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['book_id', 'user_id', 'rating', 'comment'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    // La recensione appartiene a un libro
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // La recensione appartiene a un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
