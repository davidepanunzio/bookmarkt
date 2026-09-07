<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    // Possibili stati di una segnalazione, nell'ordine naturale del loro flusso
    public const STATUS_NUOVO = 'nuovo';

    public const STATUS_IN_LAVORAZIONE = 'in_lavorazione';

    public const STATUS_RISOLTO = 'risolto';

    // Etichette leggibili da mostrare nell'interfaccia
    public const STATUS_LABELS = [
        self::STATUS_NUOVO => 'Nuova',
        self::STATUS_IN_LAVORAZIONE => 'In lavorazione',
        self::STATUS_RISOLTO => 'Risolta',
    ];

    protected $fillable = ['user_id', 'order_id', 'subject', 'message', 'status', 'admin_reply', 'replied_at'];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    // La segnalazione appartiene a un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // La segnalazione può fare riferimento a un ordine specifico (facoltativo)
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Etichetta leggibile dello stato corrente
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    // Vero se l'amministratore ha già risposto
    public function hasReply(): bool
    {
        return ! is_null($this->admin_reply);
    }
}
