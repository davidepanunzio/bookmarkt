<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    // Campi che possono essere assegnati in massa (es. tramite form)
    protected $fillable = ['name', 'slug'];

    // Una categoria ha molti libri
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
