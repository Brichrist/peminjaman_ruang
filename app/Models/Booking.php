<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'booking_date',
        'start_time',
        'end_time',
        'activity',
        'status',
        'cancelled_by',
        'cancellation_reason',
        'guest_name',
        'guest_phone'
    ];

    protected $casts = [
        'booking_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get contact name - prioritize guest_name, fallback to user name
     */
    public function getContactName()
    {
        return $this->guest_name ?? $this->user?->name ?? 'Unknown';
    }

    /**
     * Get contact phone - prioritize guest_phone, fallback to user whatsapp
     */
    public function getContactPhone()
    {
        return $this->guest_phone ?? $this->user?->whatsapp;
    }

    /**
     * Format phone number for WhatsApp (convert 08xx to 628xx)
     */
    public function getFormattedWhatsApp()
    {
        $phone = $this->getContactPhone();

        if (!$phone) {
            return null;
        }

        // Convert 08xx to 628xx
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }

    public function isUpcoming()
    {
        $bookingDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->start_time);
        return $bookingDateTime->isFuture();
    }

    public function isPast()
    {
        $bookingDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->end_time);
        return $bookingDateTime->isPast();
    }

    public function isOngoing()
    {
        $now = Carbon::now();
        $startDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->start_time);
        $endDateTime = Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->end_time);

        return $now->between($startDateTime, $endDateTime);
    }

    public function canBeCancelled()
    {
        return $this->status === 'approved' && $this->isUpcoming();
    }

    public function getDurationInMinutes()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $end->diffInMinutes($start);
    }

    public static function getTimeSlots()
    {
        $slots = [];
        $startHour = 5; // 8 AM
        $endHour = 23; // 8 PM

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $time = sprintf('%02d:%02d', $hour, $minute);
                $slots[] = $time;
            }
        }

        // Add the last slot
        $slots[] = sprintf('%02d:00', $endHour);

        return $slots;
    }

    public static function isTimeSlotAvailable($roomId, $date, $startTime, $endTime)
    {
        return !self::where('room_id', $roomId)
            ->where('booking_date', $date)
            ->where('status', 'approved')
            ->where(function ($query) use ($startTime, $endTime) {
                // Check for overlapping time slots
                // Two time ranges overlap if start1 < end2 AND end1 > start2
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();
    }
}