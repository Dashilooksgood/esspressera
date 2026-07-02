<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'status',
        'method',
        'card_last4',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    // not_required: tidak perlu deposit | unpaid: menunggu bayar | paid: lunas | refunded: dana dikembalikan
    public const STATUSES = [
        'not_required' => 'Tidak perlu deposit',
        'unpaid' => 'Belum dibayar',
        'paid' => 'Lunas',
        'refunded' => 'Dana dikembalikan',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
