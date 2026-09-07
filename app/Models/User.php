<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Valori possibili per il campo "role"
    public const ROLE_USER = 'user';

    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * Nota: il form pubblico di registrazione (RegisteredUserController) passa solo
     * name/email/password esplicitamente, quindi un utente non può auto-assegnarsi
     * il ruolo admin anche se "role" è qui presente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Ogni utente ha un solo carrello
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    // Ogni utente ha molti ordini
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Vero se l'utente è un amministratore (può gestire il catalogo)
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Libri salvati tra i preferiti dall'utente (relazione molti-a-molti tramite la tabella "wishlists")
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'wishlists')->withTimestamps();
    }

    // Segnalazioni inviate dall'utente agli amministratori
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Recensioni scritte dall'utente
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
