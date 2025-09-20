<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class AddSampleBookings extends Seeder
{
    public function run(): void
    {
        $this->command->info('Adding sample bookings for today...');

        // Get users and rooms
        $budi = User::where('email', 'budi@gmail.com')->first();
        $sarah = User::where('email', 'sarah@gmail.com')->first();
        $rooms = Room::all();

        if (!$budi || !$sarah || $rooms->isEmpty()) {
            $this->command->error('Required data not found. Run SimpleDataSeeder first.');
            return;
        }

        $today = Carbon::today()->format('Y-m-d');

        // Clear today's bookings for fresh testing
        Booking::where('booking_date', $today)->delete();

        // Create bookings for today
        $bookings = [
            [
                'user_id' => $budi->id,
                'room_id' => $rooms[0]->id, // HALL TEMPAT IBADAH
                'booking_date' => $today,
                'start_time' => '09:00',
                'end_time' => '10:30',
                'activity' => 'Ibadah Pagi',
                'status' => 'approved'
            ],
            [
                'user_id' => $sarah->id,
                'room_id' => $rooms[0]->id, // HALL TEMPAT IBADAH
                'booking_date' => $today,
                'start_time' => '14:00',
                'end_time' => '16:00',
                'activity' => 'Persekutuan Wanita',
                'status' => 'approved'
            ],
            [
                'user_id' => $budi->id,
                'room_id' => $rooms[3]->id, // RUANG MEETING
                'booking_date' => $today,
                'start_time' => '10:00',
                'end_time' => '12:00',
                'activity' => 'Rapat Koordinasi Tim',
                'status' => 'approved'
            ],
            [
                'user_id' => $sarah->id,
                'room_id' => $rooms[3]->id, // RUANG MEETING
                'booking_date' => $today,
                'start_time' => '13:30',
                'end_time' => '15:00',
                'activity' => 'Meeting dengan Vendor',
                'status' => 'approved'
            ],
            [
                'user_id' => $budi->id,
                'room_id' => $rooms[9]->id, // AUDITORIUM
                'booking_date' => $today,
                'start_time' => '18:00',
                'end_time' => '20:00',
                'activity' => 'Konser Musik Rohani',
                'status' => 'approved'
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
            $user = User::find($bookingData['user_id']);
            $room = Room::find($bookingData['room_id']);
            $this->command->info("✓ Created booking: {$room->name} - {$bookingData['start_time']}-{$bookingData['end_time']} ({$user->name})");
        }

        $this->command->info('Sample bookings created successfully!');
    }
}