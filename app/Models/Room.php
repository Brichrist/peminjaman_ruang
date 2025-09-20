<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'capacity',
        'status'
    ];

    protected $casts = [
        'capacity' => 'integer'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getUpcomingBookings()
    {
        return $this->bookings()
            ->where('booking_date', '>=', now()->toDateString())
            ->where('status', 'approved')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();
    }
}
