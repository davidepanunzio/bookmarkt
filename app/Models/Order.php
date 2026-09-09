<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    // Possibili stati di un ordine, nell'ordine naturale del loro flusso
    public const STATUS_IN_ATTESA = 'in_attesa';

    public const STATUS_PAGATO = 'pagato';

    public const STATUS_SPEDITO = 'spedito';

    public const STATUS_CONSEGNATO = 'consegnato';

    public const STATUS_ANNULLATO = 'annullato';

    // Etichette leggibili da mostrare nell'interfaccia, associate a ciascuno stato
    public const STATUS_LABELS = [
        self::STATUS_IN_ATTESA => 'In attesa',
        self::STATUS_PAGATO => 'Pagato',
        self::STATUS_SPEDITO => 'Spedito',
        self::STATUS_CONSEGNATO => 'Consegnato',
        self::STATUS_ANNULLATO => 'Annullato',
    ];

    // Metodi di pagamento supportati (nessun vero gateway: sono scelte dichiarate dal cliente)
    public const PAYMENT_CONTRASSEGNO = 'contrassegno';

    public const PAYMENT_BONIFICO = 'bonifico';

    public const PAYMENT_LABELS = [
        self::PAYMENT_CONTRASSEGNO => 'Contrassegno (pagamento alla consegna)',
        self::PAYMENT_BONIFICO => 'Bonifico bancario',
    ];

    protected $fillable = ['user_id', 'total', 'status', 'shipping_address', 'payment_method', 'note'];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    // L'ordine appartiene a un utente
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // L'ordine ha molte righe (una per ogni libro acquistato)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Etichetta leggibile dello stato corrente (es. "in_attesa" -> "In attesa")
    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    // Etichetta leggibile del metodo di pagamento scelto
    public function paymentMethodLabel(): string
    {
        return self::PAYMENT_LABELS[$this->payment_method] ?? $this->payment_method;
    }
}
