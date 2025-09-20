<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class RoomAndBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'whatsapp' => '081234567890',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create rooms based on the image list
        $rooms = [
            [
                'name' => 'HALL TEMPAT IBADAH',
                'description' => 'Hall utama untuk tempat ibadah dengan kapasitas besar.',
                'capacity' => 200,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG 3M',
                'description' => 'Hall ruang 3M untuk kegiatan umum.',
                'capacity' => 50,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG SERBAGUNA',
                'description' => 'Ruang serbaguna yang dapat digunakan untuk berbagai kegiatan.',
                'capacity' => 100,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG MEETING',
                'description' => 'Ruang khusus untuk meeting dengan fasilitas lengkap.',
                'capacity' => 20,
                'status' => 'available'
            ],
            [
                'name' => 'MENARA DOA',
                'description' => 'Ruang khusus untuk berdoa dan meditasi.',
                'capacity' => 30,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG PERPUSTAKAAN',
                'description' => 'Ruang perpustakaan untuk membaca dan belajar.',
                'capacity' => 40,
                'status' => 'available'
            ],
            [
                'name' => 'RUANG PENGAJARAN',
                'description' => 'Ruang untuk kegiatan pengajaran dan pembelajaran.',
                'capacity' => 50,
                'status' => 'available'
            ],
            [
                'name' => 'SELASAR TIMUR (DEKAT TOILET)',
                'description' => 'Area selasar timur dekat toilet untuk kegiatan kecil.',
                'capacity' => 15,
                'status' => 'available'
            ],
            [
                'name' => 'SELASAR BARAT (DEKAT RUANG MEETING)',
                'description' => 'Area selasar barat dekat ruang meeting.',
                'capacity' => 15,
                'status' => 'available'
            ],
            [
                'name' => 'AUDITORIUM',
                'description' => 'Auditorium besar untuk acara-acara khusus.',
                'capacity' => 300,
                'status' => 'available'
            ],
            [
                'name' => 'HALL RUANG SM',
                'description' => 'Hall untuk kegiatan Sekolah Minggu.',
                'capacity' => 80,
                'status' => 'available'
            ]
        ];

        // Create or update rooms
        foreach ($rooms as $roomData) {
            Room::firstOrCreate(
                ['name' => $roomData['name']],
                $roomData
            );
        }

        $this->command->info('Rooms created/updated successfully!');

        // Import bookings from Excel if needed
        if ($this->command->confirm('Do you want to import bookings from Excel file?')) {
            $this->importBookingsFromExcel();
        }
    }

    private function importBookingsFromExcel()
    {
        $inputFileName = base_path('.claude/raw/NICC SCHEDULE.xlsx');

        if (!file_exists($inputFileName)) {
            $this->command->error('Excel file not found: ' . $inputFileName);
            return;
        }

        try {
            $spreadsheet = IOFactory::load($inputFileName);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            $importedCount = 0;
            $skippedCount = 0;

            for ($row = 51; $row <= $highestRow; $row++) { // Start from row 51 as that's where actual data starts
                try {
                    // Read row data
                    $timestamp = $worksheet->getCell([1, $row])->getValue(); // Column A
                    $startDate = $worksheet->getCell([2, $row])->getValue(); // Column B
                    $endDate = $worksheet->getCell([3, $row])->getValue(); // Column C
                    $activity = $worksheet->getCell([4, $row])->getValue(); // Column D
                    $roomName = $worksheet->getCell([5, $row])->getValue(); // Column E
                    $borrowerName = $worksheet->getCell([6, $row])->getValue(); // Column F
                    $email = $worksheet->getCell([7, $row])->getValue(); // Column G

                    // Debug output - commented out for production
                    // $this->command->info("Row $row: Start=$startDate, Room=$roomName, Name=$borrowerName, Email=$email");

                    // Skip if essential data is missing
                    if (!$startDate || !$roomName || !$borrowerName || !$email) {
                        $this->command->warn("Row $row skipped: Missing essential data");
                        $skippedCount++;
                        continue;
                    }

                    // Convert Excel dates to PHP DateTime
                    $startDateTime = Date::excelToDateTimeObject($startDate);
                    $endDateTime = Date::excelToDateTimeObject($endDate);

                    // Find or create room
                    $room = Room::where('name', 'LIKE', '%' . trim($roomName) . '%')->first();
                    if (!$room) {
                        // Create room if not found
                        $room = Room::create([
                            'name' => trim($roomName),
                            'description' => 'Ruangan dari import Excel',
                            'capacity' => 30,
                            'status' => 'available'
                        ]);
                    }

                    // Find or create user
                    $user = User::firstOrCreate(
                        ['email' => strtolower(trim($email))],
                        [
                            'name' => trim($borrowerName),
                            'password' => Hash::make('password123'),
                            'whatsapp' => '081234567890', // Default WhatsApp number
                            'is_admin' => false,
                            'email_verified_at' => now()
                        ]
                    );

                    // Extract date and time
                    $bookingDate = Carbon::instance($startDateTime)->toDateString();
                    $startTime = Carbon::instance($startDateTime)->format('H:i:s');
                    $endTime = Carbon::instance($endDateTime)->format('H:i:s');

                    // Check if booking already exists for this user, room, and time
                    $existingBooking = Booking::where('user_id', $user->id)
                        ->where('room_id', $room->id)
                        ->where('booking_date', $bookingDate)
                        ->where('start_time', $startTime)
                        ->first();

                    if (!$existingBooking) {
                        // Create booking
                        Booking::create([
                            'user_id' => $user->id,
                            'room_id' => $room->id,
                            'booking_date' => $bookingDate,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'activity' => trim($activity) ?: 'Kegiatan dari import',
                            'status' => 'approved'
                        ]);
                        $importedCount++;
                    } else {
                        $skippedCount++;
                    }

                } catch (\Exception $e) {
                    $this->command->warn("Error on row $row: " . $e->getMessage());
                    $skippedCount++;
                }
            }

            $this->command->info("Import completed! Imported: $importedCount bookings, Skipped: $skippedCount");

        } catch (\Exception $e) {
            $this->command->error("Error reading Excel file: " . $e->getMessage());
        }
    }
}