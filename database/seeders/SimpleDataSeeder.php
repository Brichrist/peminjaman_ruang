<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SimpleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate all data
        $this->command->info('Truncating existing data...');
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Booking::truncate();
        Room::truncate();
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 3 users (1 admin, 2 regular users)
        $this->command->info('Creating users...');

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@nicc.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'whatsapp' => '087654321098',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Sarah Wijaya',
            'email' => 'sarah@gmail.com',
            'whatsapp' => '089876543210',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Create 11 rooms
        $this->command->info('Creating rooms...');

        $rooms = [
            [
                'name' => 'HALL TEMPAT IBADAH',
                'description' => 'Hall utama untuk tempat ibadah dengan kapasitas besar, dilengkapi sound system dan AC.',
                'capacity' => 200,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG 3M',
                'description' => 'Hall ruang 3M untuk kegiatan umum, cocok untuk acara menengah.',
                'capacity' => 50,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG SERBAGUNA',
                'description' => 'Ruang serbaguna yang dapat digunakan untuk berbagai kegiatan dan acara.',
                'capacity' => 100,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG MEETING',
                'description' => 'Ruang khusus untuk meeting dengan fasilitas lengkap, proyektor dan whiteboard.',
                'capacity' => 20,
                'status' => 'available'
            ],
            [
                'name' => 'MENARA DOA',
                'description' => 'Ruang khusus untuk berdoa dan meditasi dengan suasana tenang.',
                'capacity' => 30,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG PERPUSTAKAAN',
                'description' => 'Ruang perpustakaan untuk membaca dan belajar dengan koleksi buku lengkap.',
                'capacity' => 40,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG PENGAJARAN',
                'description' => 'Ruang untuk kegiatan pengajaran dan pembelajaran dengan fasilitas edukatif.',
                'capacity' => 50,
                'status' => 'available'
            ],
            [
                'name' => 'SELASAR TIMUR (DEKAT TOILET)',
                'description' => 'Area selasar timur dekat toilet untuk kegiatan kecil dan gathering.',
                'capacity' => 15,
                'status' => 'available'
            ],
            [
                'name' => 'SELASAR BARAT (DEKAT RUANG MEETING)',
                'description' => 'Area selasar barat dekat ruang meeting untuk kegiatan informal.',
                'capacity' => 15,
                'status' => 'available'
            ],
            [
                'name' => 'AUDITORIUM',
                'description' => 'Auditorium besar untuk acara-acara khusus dengan panggung dan sound system profesional.',
                'capacity' => 300,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG SM',
                'description' => 'Hall untuk kegiatan Sekolah Minggu dengan dekorasi dan fasilitas khusus anak.',
                'capacity' => 80,
                'status' => 'available'
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }

        // Create some sample bookings for demonstration
        $this->command->info('Creating sample bookings...');

        // Booking 1: Budi books Hall Tempat Ibadah for tomorrow
        Booking::create([
            'user_id' => $user1->id,
            'room_id' => 1, // HALL TEMPAT IBADAH
            'booking_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
            'activity' => 'Ibadah Mingguan',
            'status' => 'approved'
        ]);

        // Booking 2: Sarah books Ruang Meeting for tomorrow
        Booking::create([
            'user_id' => $user2->id,
            'room_id' => 4, // RUANG MEETING
            'booking_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'activity' => 'Rapat Tim',
            'status' => 'approved'
        ]);

        // Booking 3: Budi books Auditorium for next week
        Booking::create([
            'user_id' => $user1->id,
            'room_id' => 10, // AUDITORIUM
            'booking_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'activity' => 'Konser Musik',
            'status' => 'approved'
        ]);

        $this->command->info('Data seeding completed successfully!');
        $this->command->info('Admin: admin@nicc.com / password');
        $this->command->info('User 1: budi@gmail.com / password');
        $this->command->info('User 2: sarah@gmail.com / password');
    }
}