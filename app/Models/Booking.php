<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'customer_name',
        'phone',
        'email',
        'date',
        'time',
        'duration',
        'party_size',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'duration' => 'integer',
        'party_size' => 'integer',
    ];

    public const STATUSES = [
        'pending' => 'Menunggu pembayaran',
        'confirmed' => 'Terkonfirmasi',
        'cancelled' => 'Dibatalkan',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function endTime(): string
    {
        return Carbon::createFromFormat('H:i', $this->time)
            ->addMinutes($this->duration)
            ->format('H:i');
    }

    /**
     * Cek apakah sebuah kamar/meja tersedia pada tanggal & jam tertentu.
     */
    public static function isRoomAvailable(int $roomId, string $date, string $time, int $duration, ?int $excludeBookingId = null): bool
    {
        $newStart = self::toMinutes($time);
        $newEnd = $newStart + $duration;

        $query = self::query()
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->active();

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        foreach ($query->get(['id', 'time', 'duration']) as $existing) {
            $exStart = self::toMinutes($existing->time);
            $exEnd = $exStart + $existing->duration;

            if ($newStart < $exEnd && $exStart < $newEnd) {
                return false;
            }
        }

        return true;
    }

    public static function toMinutes(string $hhmm): int
    {
        [$h, $m] = explode(':', $hhmm);
        return ((int) $h) * 60 + (int) $m;
    }
}
