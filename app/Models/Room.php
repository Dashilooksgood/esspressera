<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'deposit',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'deposit' => 'integer',
    ];

    // Tipe kamar/meja yang tersedia
    public const TYPES = [
        'standard' => 'Meja standar',
        'komunal' => 'Meja komunal / bar',
        'privat' => 'Ruang privat',
        'outdoor' => 'Area luar ruangan',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
