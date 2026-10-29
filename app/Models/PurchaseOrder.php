<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'supplier_id', 'user_id', 'status', 'ordered_at', 'received_at'];

    protected $casts = [
        'ordered_at' => 'date',
        'received_at' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function total(): float
    {
        return (float) $this->items->sum(fn ($item) => $item->quantity * $item->unit_price);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'brouillon' => 'Brouillon',
            'envoyee' => 'Envoyée',
            'recue' => 'Reçue',
            'annulee' => 'Annulée',
            default => $this->status,
        };
    }

    public function statusClass(): string
    {
        return match ($this->status) {
            'brouillon' => 'bg-gray-100 text-gray-700',
            'envoyee' => 'bg-blue-100 text-blue-700',
            'recue' => 'bg-green-100 text-green-700',
            'annulee' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function canBeReceived(): bool
    {
        return in_array($this->status, ['brouillon', 'envoyee'], true);
    }
}
