<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'stock',
        'cover_image',
        'category_id',
        'author_id',
    ];

    // Cast automatico dei tipi quando leggiamo/scriviamo il modello
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    // Ogni libro appartiene a una categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Ogni libro appartiene a un autore
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // Righe di carrello in cui compare questo libro
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Righe d'ordine in cui compare questo libro
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Recensioni lasciate dagli utenti per questo libro
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Vero se il libro è ancora disponibile in magazzino
    public function isDisponibile(): bool
    {
        return $this->stock > 0;
    }

    // URL della copertina, oppure un placeholder se il libro non ne ha una
    public function coverUrl(): string
    {
        return $this->cover_image
            ? Storage::url($this->cover_image)
            : asset('images/book-placeholder.svg');
    }
}
