<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'whatsapp' => '081234567890',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create regular user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'whatsapp' => '081987654321',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'email_verified_at' => now(),
        ]);

        // Create sample rooms
        $rooms = [
            [
                'name' => 'Ruang Meeting A',
                'description' => 'Ruangan meeting dengan kapasitas 10 orang, dilengkapi proyektor dan whiteboard.',
                'capacity' => 10,
                'status' => 'available'
            ],
            [
                'name' => 'Ruang Meeting B',
                'description' => 'Ruangan meeting dengan kapasitas 15 orang, dilengkapi proyektor, whiteboard, dan AC.',
                'capacity' => 15,
                'status' => 'available'
            ],
            [
                'name' => 'Ruang Rapat Utama',
                'description' => 'Ruangan rapat besar dengan kapasitas 30 orang, dilengkapi dengan fasilitas lengkap.',
                'capacity' => 30,
                'status' => 'available'
            ],
            [
                'name' => 'Ruang Diskusi',
                'description' => 'Ruangan diskusi kecil dengan suasana nyaman untuk 5 orang.',
                'capacity' => 5,
                'status' => 'available'
            ],
            [
                'name' => 'Ruang Presentasi',
                'description' => 'Ruangan presentasi dengan kapasitas 20 orang, dilengkapi proyektor HD dan sound system.',
                'capacity' => 20,
                'status' => 'available'
            ]
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $this->command->info('Admin user and sample rooms created successfully!');
        $this->command->info('Admin credentials: admin@example.com / password');
        $this->command->info('User credentials: user@example.com / password');
    }
}