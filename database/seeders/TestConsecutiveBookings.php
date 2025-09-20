<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class TestConsecutiveBookings extends Seeder
{
    public function run(): void
    {
        $this->command->info('Testing consecutive bookings...');

        // Get first user and room
        $user1 = User::where('email', 'budi@gmail.com')->first();
        $user2 = User::where('email', 'sarah@gmail.com')->first();
        $room = Room::first();

        if (!$user1 || !$user2 || !$room) {
            $this->command->error('Required users or room not found. Run SimpleDataSeeder first.');
            return;
        }

        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Clear tomorrow's bookings for testing
        Booking::where('booking_date', $tomorrow)->delete();

        // Test Case 1: Create booking from 11:30 to 12:00
        $booking1 = Booking::create([
            'user_id' => $user1->id,
            'room_id' => $room->id,
            'booking_date' => $tomorrow,
            'start_time' => '11:30',
            'end_time' => '12:00',
            'activity' => 'Meeting Part 1',
            'status' => 'approved'
        ]);
        $this->command->info('✓ Created Booking 1: 11:30 - 12:00');

        // Test Case 2: Try to create consecutive booking from 12:00 to 12:30
        $canBook = Booking::isTimeSlotAvailable(
            $room->id,
            $tomorrow,
            '12:00',
            '12:30'
        );

        if ($canBook) {
            $booking2 = Booking::create([
                'user_id' => $user2->id,
                'room_id' => $room->id,
                'booking_date' => $tomorrow,
                'start_time' => '12:00',
                'end_time' => '12:30',
                'activity' => 'Meeting Part 2',
                'status' => 'approved'
            ]);
            $this->command->info('✓ Successfully created consecutive Booking 2: 12:00 - 12:30');
        } else {
            $this->command->error('✗ Failed to create consecutive booking at 12:00 - 12:30');
        }

        // Test Case 3: Try overlapping booking (should fail)
        $canBookOverlap = Booking::isTimeSlotAvailable(
            $room->id,
            $tomorrow,
            '11:45',
            '12:15'
        );

        if (!$canBookOverlap) {
            $this->command->info('✓ Correctly prevented overlapping booking at 11:45 - 12:15');
        } else {
            $this->command->error('✗ Failed to prevent overlapping booking');
        }

        // Test Case 4: Create another consecutive booking from 12:30 to 13:00
        $canBook3 = Booking::isTimeSlotAvailable(
            $room->id,
            $tomorrow,
            '12:30',
            '13:00'
        );

        if ($canBook3) {
            $booking3 = Booking::create([
                'user_id' => $user1->id,
                'room_id' => $room->id,
                'booking_date' => $tomorrow,
                'start_time' => '12:30',
                'end_time' => '13:00',
                'activity' => 'Meeting Part 3',
                'status' => 'approved'
            ]);
            $this->command->info('✓ Successfully created consecutive Booking 3: 12:30 - 13:00');
        } else {
            $this->command->error('✗ Failed to create consecutive booking at 12:30 - 13:00');
        }

        $this->command->info('Test completed!');
    }
}